<?php

namespace Kinoba\CouponBundle\Model;

interface GeneratedCouponInterface
{
    /**
     * Get the value of Coupon
     *
     * @return CouponUserInterface
     */
    public function getCoupon();

    /**
     * Set the value of Coupon
     *
     * @param CouponInterface coupon
     *
     * @return self
     */
    public function setCoupon(CouponInterface $coupon);

    /**
     * Get the value of User
     *
     * @return CouponUserInterface
     */
    public function getUser();

    /**
     * Set the value of User
     *
     * @param CouponUserInterface user
     *
     * @return self
     */
    public function setUser(CouponUserInterface $user);

    /**
     * Get the value of Bill
     *
     * @return BillableInterface
     */
    public function getBill();

    /**
     * Set the value of Bill
     *
     * @param BillableInterface bill
     *
     * @return self
     */
    public function setBill(BillableInterface $bill);

    /**
     * Get the value of Used At
     *
     * @return \DateTime
     */
    public function getUsedAt();

    /**
     * Set the value of Used At
     *
     * @param \DateTime usedAt
     *
     * @return self
     */
    public function setUsedAt(\DateTime $usedAt);
}
