<?php

namespace Kinoba\CouponBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Kinoba\CouponBundle\Entity\Coupon;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CouponRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    /**
     * Finds all codes and returns as one dimentional array
     * @return array
     */
    public function findAllCodes()
    {
        $query = $this->createQueryBuilder('c')
            ->select('c.code')
        ;

        return array_column($query->getQuery()->getArrayResult(), 'code');
    }

    /**
     * Finds if a coupon has already been used by this user
     * @param $coupon
     * @param $user
     */
    public function findByIdAndByUser($coupon, $user)
    {
        $query = $this->createQueryBuilder('c')
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
}
