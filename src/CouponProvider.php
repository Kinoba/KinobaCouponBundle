<?php

namespace Kinoba\CouponBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Kinoba\CouponBundle\Model\Coupon;
use Kinoba\CouponBundle\Model\GeneratedCoupon;
use Kinoba\CouponBundle\Model\CouponInterface;
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
    * @var string
    */
    private $class;

    /**
    * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em, $defaultMask, $characters, $class)
    {
        $this->em = $em;
        $this->mask = $defaultMask;
        $this->characters = $characters;
        $this->class = $class;

        // Finds all generated codes
        $this->codes = $this->findAllCodes();
    }

    /**
     * Create a coupon in database
     *
     * @param string $amount
     * @param int $type
     * @param int $maxNumber
     * @param \DateTime $expiredAt
     * @param string $mask
     *
     * @return Coupon
     */
    public function create($amount = 1, $type = Coupon::TYPE_FIXED, $maxNumber = 1, $expiredAt = null, $mask = null)
    {
        $code = $this->generate($mask);

        while (!$this->validate($code)) {
            $code = $this->generate();
        }

        $coupon = new Coupon($amount, $code, $type, $maxNumber, $expiredAt);

        $this->em->persist($coupon);
        $this->em->flush();

        return $coupon;
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
        $coupon = $this->getEntityRepository()->findOneBy([
            'code' => $code,
            'active' => true,
        ]);

        if ($coupon === null) {
            throw new Exceptions\InvalidCouponException;
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
    * Generate single code using parameters from config.
    *
    * @param $mask
    * @return string
    */
    public function generate($mask)
    {
        // Change mask
        if ($mask) {
            $this->mask = $mask;
        }

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
    * Check if user is trying to apply code again.
    *
    * @param Coupon $coupon
    *
    * @return bool
    */
    public function isSecondUsageAttempt(Coupon $coupon, CouponUserInterface $user)
    {
        return $this->findByIdAndByUser($coupon, $user) ? true : false;
    }

    /**
     * Returns the default mask
     * @return string
     */
    public function getDefaultMask()
    {
        return $this->mask;
    }

    /**
     * Finds all codes in coupons table
     * @return array
     */
    public function findAllCodes()
    {
        $query = $this->getEntityRepository()->createQueryBuilder('c')
            ->select('c.code')
        ;

        return array_column($query->getQuery()->getArrayResult(), 'code');
    }

    /**
     * Finds if a coupon has already been used by this user
     * @param CouponInterface $coupon
     * @param CouponUserInterface $user
     * @return ArrayCollection
     */
    public function findByIdAndByUser(CouponInterface $coupon, CouponUserInterface $user)
    {
        $query = $this->getEntityRepository()->createQueryBuilder('c')
            ->leftJoin('c.generatedCoupons', 'g')
            ->where('c.id = :id')
            ->andWhere('g.user = :user_id')
            ->setParameters([
                'id' => $coupon,
                'user_id' => $user
            ])
        ;

        return $query->getQuery()->getResult();
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
    * Returns the CouponRepository
    */
    private function getEntityRepository()
    {
        $metadata = $this->em->getClassMetadata($this->class);
        return $this->em->getRepository($metadata->getName());
    }
}
