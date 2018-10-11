<?php

namespace Kinoba\CouponBundle;

// use Kinoba\CouponBundle\DependencyInjection\Compiler\SetTwigVariablesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KinobaCouponBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // $container
        //     ->addCompilerPass(new SetTwigVariablesPass())
        // ;
    }
}
