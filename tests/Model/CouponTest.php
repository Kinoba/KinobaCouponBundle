<?php

namespace Kinoba\CouponBundle\Tests\Model;

use Kinoba\CouponBundle\Model\Coupon;
use Kinoba\CouponBundle\Model\GeneratedCoupon;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class CouponTest extends TestCase
{
    public function testConstruct()
    {
        $date = new \DateTime();
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $this->assertEquals(1, $coupon->getType());
    }

    public function testCreatedAt()
    {
        $date = new \DateTime();
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $this->assertNull($coupon->getCreatedAt());
        $coupon->setCreatedAt($date);

        $this->assertEquals($date, $coupon->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $date = new \DateTime();
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $this->assertNull($coupon->getUpdatedAt());
        $coupon->setUpdatedAt($date);

        $this->assertEquals($date, $coupon->getUpdatedAt());
    }

    public function testIsExpired()
    {
        $date = new \DateTime('-1 day');
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setExpiredAt($date);

        $this->assertTrue($coupon->isExpired());
    }

    public function testIsNotExpired()
    {
        $date = new \DateTime('1 day');
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setExpiredAt($date);

        $this->assertFalse($coupon->isExpired());
    }

    public function testIsNotExpiredWithExpiredAtNull()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);

        $this->assertFalse($coupon->isExpired());
    }

    public function testIsMaxNumberReach()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setMaxNumber(2);

        for ($i = 0; $i < 2; $i++) {
            $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
            $coupon->addGeneratedCoupon($gc);
        }

        $this->assertTrue($coupon->isMaxNumberReach());
    }

    public function testIsMaxNumberNotReach()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setMaxNumber(10);

        for ($i = 0; $i < 5; $i++) {
            $gc = $this->getMockForAbstractClass(GeneratedCoupon::class);
            $coupon->addGeneratedCoupon($gc);
        }

        $this->assertFalse($coupon->isMaxNumberReach());
    }

    public function testGetDiscountWithPercentage()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_PERCENTAGE);
        $coupon->setAmount(10);

        $this->assertEquals(1, $coupon->getDiscount(10));
    }

    public function testGetDiscountedPriceWithPercentage()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_PERCENTAGE);
        $coupon->setAmount(10);


        $this->assertEquals(9, $coupon->getDiscountedPrice(10));
    }

    public function testGetDiscountWithFixed()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_FIXED);
        $coupon->setAmount(10);

        $this->assertEquals(10, $coupon->getDiscount(15));
    }

    public function testGetDiscountedPriceWithFixed()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_FIXED);
        $coupon->setAmount(10);

        $this->assertEquals(5, $coupon->getDiscountedPrice(15));
    }

    public function testGetDiscountWithFixedTooHigh()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_FIXED);
        $coupon->setAmount(10);

        $this->assertEquals(5, $coupon->getDiscount(5));
    }

    public function testGetDiscountedPriceWithFixedTooHigh()
    {
        $coupon = $this->getMockForAbstractClass(Coupon::class);
        $coupon->setType(Coupon::TYPE_FIXED);
        $coupon->setAmount(10);

        $this->assertEquals(0, $coupon->getDiscountedPrice(5));
    }
}
