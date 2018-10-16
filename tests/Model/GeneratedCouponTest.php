<?php

namespace Kinoba\CouponBundle\Tests\Model;

use Kinoba\CouponBundle\Model\Coupon;
use Kinoba\CouponBundle\Model\GeneratedCoupon;
use Kinoba\CouponBundle\Model\BillableInterface;
use Kinoba\CouponBundle\Model\CouponUserInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class GeneratedCouponTest extends TestCase
{
    public function testConstruct()
    {
        $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
        $this->assertInstanceOf(\DateTime::class, $gc->getUsedAt());
    }

    public function testUsedAt()
    {
        $date = new \DateTime('-1 day');
        $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
        $gc->setUsedAt($date);

        $this->assertEquals($date, $gc->getUsedAt());
    }

    public function testUser()
    {
        $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
        $user = $this->getMockForAbstractClass(CouponUserInterface::class);
        $gc->setUser($user);

        $this->assertEquals($user, $gc->getUser());
    }

    public function testBill()
    {
        $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
        $bill = $this->getMockForAbstractClass(BillableInterface::class);
        $gc->setBill($bill);

        $this->assertEquals($bill, $gc->getBill());
    }
}
