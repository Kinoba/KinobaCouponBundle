<?php

namespace Kinoba\CouponBundle\Exceptions;

use Exception;

class AlreadyUsedException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Coupon code is already used by current user.';

    /**
     * @var int
     */
    protected $code = 403;
}
