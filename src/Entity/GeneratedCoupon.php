<?php

namespace Kinoba\CouponBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Kinoba\CouponBundle\Model\Base;
use Kinoba\CouponBundle\Model\CouponUserInterface;
use Kinoba\CouponBundle\Model\BillableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="generated_coupons")
 * @ORM\Entity
 */
class GeneratedCoupon
{
    use Base\IdentifiableEntityTrait;

    /**
     * @var Coupon
     *
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="generatedCoupons")
     * @ORM\JoinColumn(name="coupon_id", referencedColumnName="id")
     */
    protected $coupon;

    /**
     * @var CouponUserInterface
     *
     * @ORM\ManyToOne(targetEntity="Kinoba\CouponBundle\Model\CouponUserInterface")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var BillableInterface
     *
     * @ORM\ManyToOne(targetEntity="Kinoba\CouponBundle\Model\BillableInterface")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id")
     */
    protected $bill;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="used_at", type="datetime")
     */
    protected $usedAt;

    public function __construct(CouponUserInterface $user = null, BillableInterface $bill = null)
    {
        $this->user = $user;
        $this->bill = $bill;
        $this->usedAt = new \DateTime();
    }

    /**
     * Get the value of Coupon
     *
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set the value of Coupon
     *
     * @param Coupon coupon
     *
     * @return self
     */
    public function setCoupon(Coupon $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get the value of User
     *
     * @return CouponUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of User
     *
     * @param CouponUserInterface user
     *
     * @return self
     */
    public function setUser(CouponUserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Bill
     *
     * @return BillableInterface
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set the value of Bill
     *
     * @param BillableInterface bill
     *
     * @return self
     */
    public function setBill(BillableInterface $bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get the value of Used At
     *
     * @return \DateTime
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * Set the value of Used At
     *
     * @param \DateTime usedAt
     *
     * @return self
     */
    public function setUsedAt(\DateTime $usedAt)
    {
        $this->usedAt = $usedAt;

        return $this;
    }
}
