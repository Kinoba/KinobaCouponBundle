<?php

namespace Kinoba\CouponBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Kinoba\CouponBundle\Model\Base;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="coupons")
 * @ORM\Entity(repositoryClass="Kinoba\CouponBundle\Repository\CouponRepository")
 */
class Coupon
{
    use Base\IdentifiableEntityTrait;
    use Base\TimestampableEntityTrait;

    const TYPE_PERCENTAGE = 0;
    const TYPE_FIXED = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    protected $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="max_number", type="integer", nullable=true)
     */
    protected $maxNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime", nullable=true)
     */
    protected $expiredAt;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    protected $type;

    /**
     * @var GeneratedCoupon
     *
     * @ORM\OneToMany(targetEntity="GeneratedCoupon", mappedBy="coupon", cascade={"persist", "remove"})
     *
     */
    protected $generatedCoupons;

    public function __construct($amount, $code, $type = self::TYPE_FIXED, $maxNumber = 1, $expiredAt = null)
    {
        $this->amount = $amount;
        $this->code = $code;
        $this->type = $type;
        $this->maxNumber = $maxNumber;
        $this->expiredAt = $expiredAt;
        $this->generatedCoupons = new ArrayCollection();
    }

    /**
     * Get the value of Code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of Code
     *
     * @param string code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of Amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of Amount
     *
     * @param float amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of Max Number
     *
     * @return integer
     */
    public function getMaxNumber()
    {
        return $this->maxNumber;
    }

    /**
     * Set the value of Max Number
     *
     * @param integer maxNumber
     *
     * @return self
     */
    public function setMaxNumber($maxNumber)
    {
        $this->maxNumber = $maxNumber;

        return $this;
    }

    /**
     * Check if maxNumber is not reach
     *
     * @return bool
     */
    public function isMaxNumberReach()
    {
        return $this->maxNumber ? ($this->maxNumber <= count($this->generatedCoupons)) : false;
    }

    /**
     * Get the value of Active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set the value of Active
     *
     * @param boolean active
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of Expired At
     *
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set the value of Expired At
     *
     * @param \DateTime expiredAt
     *
     * @return self
     */
    public function setExpiredAt(\DateTime $expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Returns if the coupon is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        $now = new \DateTime();
        return $this->expiredAt ? ($this->expiredAt < $now) : false;
    }

    /**
     * Get the value of Type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of Type
     *
     * @param integer type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the all types
     *
     * @return array
     */
    public function getTypes()
    {
        return [
            self::TYPE_PERCENTAGE,
            self::TYPE_FIXED,
        ];
    }

    /**
     * Get the value of Generated Coupons
     *
     * @return GeneratedCoupon
     */
    public function getGeneratedCoupons()
    {
        return $this->generatedCoupons;
    }

    /**
     * Set the value of Generated Coupons
     *
     * @param GeneratedCoupon generatedCoupons
     *
     * @return self
     */
    public function setGeneratedCoupons(GeneratedCoupon $generatedCoupons)
    {
        $this->generatedCoupons = $generatedCoupons;

        return $this;
    }

    /**
    * Add generated coupon
    *
    * @param GeneratedCoupon $generatedCoupon
    * @return Coupon
    */
    public function addGeneratedCoupon(GeneratedCoupon $generatedCoupon)
    {
        $generatedCoupon->setCoupon($this);
        $this->generatedCoupons[] = $generatedCoupon;

        return $this;
    }

    /**
    * Remove generated coupon
    *
    * @param GeneratedCoupon $generatedCoupon
    */
    public function removeGeneratedCoupon(GeneratedCoupon $generatedCoupon)
    {
        $this->generatedCoupons->removeElement($generatedCoupon);
    }

    /**
     * Returns the discount from a float
     * @param float $price
     * @return float
     */
    public function getDiscount(float $price) : float
    {
        $discount = $this->amount;
        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = $price * $this->amount / 100;
        } elseif ($discount > $price) {
            $discount = $price;
        }

        return $discount;
    }

    /**
     * Returns the discounted price from a float
     * @param float $price
     * @return float
     */
    public function getDiscountedPrice(float $price) : float
    {
        return $price - $this->getDiscount($price);
    }
}
