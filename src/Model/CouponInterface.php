<?php

namespace Kinoba\CouponBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

interface CouponInterface
{
    /**
     * Constructor
     */
    public function __construct(
        $amount = null,
        $code = null,
        $type = self::TYPE_FIXED,
        $maxNumber = 1,
        $expiredAt = null
    );

    /**
     * Get the value of Code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set the value of Code
     *
     * @param string code
     *
     * @return self
     */
    public function setCode($code);

    /**
     * Get the value of Amount
     *
     * @return float
     */
    public function getAmount();

    /**
     * Set the value of Amount
     *
     * @param float amount
     *
     * @return self
     */
    public function setAmount($amount);

    /**
     * Get the value of Max Number
     *
     * @return integer
     */
    public function getMaxNumber();

    /**
     * Set the value of Max Number
     *
     * @param integer maxNumber
     *
     * @return self
     */
    public function setMaxNumber($maxNumber);

    /**
     * Check if maxNumber is not reach
     *
     * @return bool
     */
    public function isMaxNumberReach();

    /**
     * Get the value of Active
     *
     * @return boolean
     */
    public function isActive();

    /**
     * Set the value of Active
     *
     * @param boolean active
     *
     * @return self
     */
    public function setActive($active);

    /**
     * Get the value of Expired At
     *
     * @return \DateTime
     */
    public function getExpiredAt();

    /**
     * Set the value of Expired At
     *
     * @param \DateTime expiredAt
     *
     * @return self
     */
    public function setExpiredAt(\DateTime $expiredAt);

    /**
     * Returns if the coupon is expired
     *
     * @return bool
     */
    public function isExpired();

    /**
     * Get the value of Type
     *
     * @return integer
     */
    public function getType();

    /**
     * Set the value of Type
     *
     * @param integer type
     *
     * @return self
     */
    public function setType($type);

    /**
     * Get the all types
     *
     * @return array
     */
    public function getTypes();

    /**
     * Get the value of Generated Coupons
     *
     * @return GeneratedCouponInterface
     */
    public function getGeneratedCoupons();

    /**
     * Set the value of Generated Coupons
     *
     * @param GeneratedCouponInterface generatedCoupons
     *
     * @return self
     */
    public function setGeneratedCoupons(GeneratedCouponInterface $generatedCoupons);

    /**
    * Add generated coupon
    *
    * @param GeneratedCouponInterface $generatedCoupon
    * @return self
    */
    public function addGeneratedCoupon(GeneratedCouponInterface $generatedCoupon);

    /**
    * Remove generated coupon
    *
    * @param GeneratedCouponInterface $generatedCoupon
    */
    public function removeGeneratedCoupon(GeneratedCouponInterface $generatedCoupon);

    /**
     * Returns the discount from a float
     * @param float $price
     * @return float
     */
    public function getDiscount(float $price) : float;

    /**
     * Returns the discounted price from a float
     * @param float $price
     * @return float
     */
    public function getDiscountedPrice(float $price) : float;
}
