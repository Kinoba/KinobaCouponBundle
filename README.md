This bundle is under construction, do not use in production

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require <package-name>
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kinoba/coupon-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Kinoba\CouponBundle\KinobaCouponBundle(),
            // ...
        );
    }
}
```

### Step 3: Configure your app

You have to add 2 entites : Coupon and GeneratedCoupon to your project
```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kinoba\CouponBundle\Model\Coupon as BaseCoupon;

/**
 * @ORM\Table(name="coupons")
 * @ORM\Entity
 */
class Coupon extends BaseCoupon
{
}
```

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kinoba\CouponBundle\Model\GeneratedCoupon as BaseGeneratedCoupon;

/**
 * @ORM\Table(name="generated_coupons")
 * @ORM\Entity
 */
class GeneratedCoupon extends BaseGeneratedCoupon
{
}

```


You need to map your entities with our interfaces.
In `doctrine.yaml` or `config.yml`, add :
```yaml
doctrine:
    orm:
        resolve_target_entities:
            Kinoba\CouponBundle\Model\CouponUserInterface: App\Entity\User
            Kinoba\CouponBundle\Model\BillableInterface: App\Entity\Offer
```

You could add a new file `kinoba_coupon.yaml` with 3 parameters in it
```yaml
kinoba_coupon:
    default_mask: KIN*****
    characters: 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ
    coupon_class: App\Entity\Coupon
    generated_coupon_class: App\Entity\GeneratedCoupon
```


### Step 4: Usage

Create a new code in the database thank to `CouponProvider`:
`create($amount = 1, $type = Coupon::TYPE_FIXED, $maxNumber = 1, $expiredAt = null, $mask = null)`

`$type`: TYPE_FIXED for currency discount or TYPE_PERCENTAGE for %

`$maxNumber`: max number of generated coupons after the coupon became invalid

`$expiredAt`: End of validity for a coupon

`$mask`: Create a coupon with a new mask (not defaultMask)

---

If you want to check if a code exists and is usable:
`check($code)`

Returns `Coupon` object if valid or `false` if not

---

If you want to check if user tries to use a code for second time you can call `isSecondUsageAttempt` and pass
Coupon object and CouponUserInterface as an argument.
Returns a boolean value

---

Apply a code:
`apply($code, $user, $bill = null)`

`$bill`: if you want to connect a generated coupon with a payment/bill/offer

Apply will check if the code is usable
