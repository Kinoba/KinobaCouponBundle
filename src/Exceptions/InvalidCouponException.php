<?php

namespace Kinoba\CouponBundle\Exceptions;

use Exception;

class InvalidPromocodeException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Invalid coupon code was passed.';
    
    /**
     * @var int
     */
    protected $code = 404;
}
