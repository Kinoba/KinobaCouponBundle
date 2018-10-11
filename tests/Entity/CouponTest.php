<?php

namespace Kinoba\CouponBundle\Tests\Entity;

use Kinoba\CouponBundle\Entity\Coupon;
use Kinoba\CouponBundle\Entity\GeneratedCoupon;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class CouponTest extends TestCase
{
    public function testConstruct()
    {
        $date = new \DateTime();
        $coupon = new Coupon(10, '12345', Coupon::TYPE_FIXED, 100, $date);

        $this->assertEquals($date, $coupon->getExpiredAt());
        $this->assertEquals(10, $coupon->getAmount());
        $this->assertEquals('12345', $coupon->getCode());
        $this->assertEquals(1, $coupon->getType());
        $this->assertEquals(100, $coupon->getMaxNumber());
    }

    public function testCreatedAt()
    {
        $date = new \DateTime();
        $coupon = new Coupon(10, 'AAAAA');
        $this->assertNull($coupon->getCreatedAt());
        $coupon->setCreatedAt($date);

        $this->assertEquals($date, $coupon->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $date = new \DateTime();
        $coupon = new Coupon(10, 'AAAAA');
        $this->assertNull($coupon->getUpdatedAt());
        $coupon->setUpdatedAt($date);

        $this->assertEquals($date, $coupon->getUpdatedAt());
    }

    public function testIsExpired()
    {
        $date = new \DateTime('-1 day');
        $coupon = new Coupon(10, 'AAAAA');
        $coupon->setExpiredAt($date);

        $this->assertTrue($coupon->isExpired());
    }

    public function testIsNotExpired()
    {
        $date = new \DateTime('1 day');
        $coupon = new Coupon(10, 'AAAAA');
        $coupon->setExpiredAt($date);

        $this->assertFalse($coupon->isExpired());
    }

    public function testIsNotExpiredWithExpiredAtNull()
    {
        $coupon = new Coupon(10, 'AAAAA');

        $this->assertFalse($coupon->isExpired());
    }

    public function testIsMaxNumberReach()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_PERCENTAGE, 2);

        for ($i = 0; $i < 2; $i++) {
            $gc = new GeneratedCoupon();
            $coupon->addGeneratedCoupon($gc);
        }

        $this->assertTrue($coupon->isMaxNumberReach());
    }

    public function testIsMaxNumberNotReach()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_PERCENTAGE, 10);

        for ($i = 0; $i < 5; $i++) {
            $gc = new GeneratedCoupon();
            $coupon->addGeneratedCoupon($gc);
        }

        $this->assertFalse($coupon->isMaxNumberReach());
    }

    public function testGetDiscountWithPercentage()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_PERCENTAGE);

        $this->assertEquals(1, $coupon->getDiscount(10));
    }

    public function testGetDiscountedPriceWithPercentage()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_PERCENTAGE);

        $this->assertEquals(9, $coupon->getDiscountedPrice(10));
    }

    public function testGetDiscountWithFixed()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_FIXED);

        $this->assertEquals(10, $coupon->getDiscount(15));
    }

    public function testGetDiscountedPriceWithFixed()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_FIXED);

        $this->assertEquals(5, $coupon->getDiscountedPrice(15));
    }

    public function testGetDiscountWithFixedTooHigh()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_FIXED);

        $this->assertEquals(5, $coupon->getDiscount(5));
    }

    public function testGetDiscountedPriceWithFixedTooHigh()
    {
        $coupon = new Coupon(10, 'AAAAA', Coupon::TYPE_FIXED);

        $this->assertEquals(0, $coupon->getDiscountedPrice(5));
    }
}
