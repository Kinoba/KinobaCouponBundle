<?php

namespace Kinoba\CouponBundle;

use Doctrine\ORM\EntityManagerInterface;
use Kinoba\CouponBundle\Entity\Coupon;
use Kinoba\CouponBundle\Entity\GeneratedCoupon;
use Kinoba\CouponBundle\Exceptions;
use Kinoba\CouponBundle\Model\CouponUserInterface;

class CouponProvider
{
    /**
    * @var EntityManagerInterface
    */
    private $em;

    /**
    * @var array
    */
    private $codes;

    /**
    * @var string
    */
    private $mask;

    /**
    * @var string
    */
    private $characters;

    /**
    * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em, $defaultMask, $characters)
    {
        $this->em = $em;
        $this->mask = $defaultMask;
        $this->characters = $characters;

        // Finds all generated codes
        $this->codes = $this->getEntityRepository()->findAllCodes();
    }

    public function create($amount = 1, $type = Coupon::TYPE_FIXED, $maxNumber = 1, $expiredAt = null, $mask = null)
    {
        // Change mask
        if ($mask) {
            $this->mask = $mask;
        }

        $code = $this->generate();

        while (!$this->validate($code)) {
            $code = $this->generate();
        }

        $coupon = new Coupon($amount, $code, $type, $maxNumber, $expiredAt);

        $this->em->persist($coupon);
        $this->em->flush();
    }

    /**
    * Check coupon in database if it is valid.
    *
    * @param string $code
    *
    * @return bool|Coupon
    * @throws Exceptions\InvalidCouponException
    */
    public function check($code)
    {
        $coupon = $this->getEntityRepository()->findOneByCode($code);
        if ($coupon === null) {
            throw new Exceptions\InvalidPromocodeException;
        }

        if ($coupon->isExpired() || $coupon->isMaxNumberReach()) {
            return false;
        }

        return $coupon;
    }

    /**
     * Apply coupon to user that it's used from now.
     *
     * @param string $code
     *
     * @return bool|Coupon
     * @throws Exceptions\AlreadyUsedException
     */
    public function apply($code, $user, $bill = null)
    {
        if ($coupon = $this->check($code)) {
            if ($this->isSecondUsageAttempt($coupon, $user)) {
                throw new Exceptions\AlreadyUsedException;
            }
            // Add GeneratedCoupon to user
            $generatedCoupon = new GeneratedCoupon($user, $bill);
            $coupon->addGeneratedCoupon($generatedCoupon);

            $this->em->flush();
        }

        return $coupon;
    }

    /**
    * Check if user is trying to apply code again.
    *
    * @param Coupon $coupon
    *
    * @return bool
    */
    public function isSecondUsageAttempt(Coupon $coupon, CouponUserInterface $user)
    {
        return $this->getEntityRepository()->findByIdAndByUser($coupon, $user) ? true : false;
    }

    /**
    * Your code will be validated to be unique for one request.
    *
    * @param $new
    *
    * @return bool
    */
    private function validate($new)
    {
        return !in_array($new, $this->codes);
    }

    /**
    * Generate single code using parameters from config.
    *
    * @return string
    */
    private function generate()
    {
        $length = substr_count($this->mask, '*');
        $charLength = strlen($this->characters) - 1;

        $code = $this->mask;

        for ($i = 1; $i <= $length; $i++) {
            $character = $this->characters[rand(0, $charLength)];
            $code = preg_replace('/\*/', $character, $code, 1);
        }

        return $code;
    }

    /**
    * Returns the CouponRepository
    */
    private function getEntityRepository()
    {
        return $this->em->getRepository(Coupon::class);
    }
}
