<?php

namespace Kinoba\CouponBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kinoba_coupon');

        $rootNode->children()
            ->scalarNode('default_mask')->defaultValue('*****')->end()
            ->scalarNode('characters')->defaultValue('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')->end()
            ->scalarNode('coupon_class')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('generated_coupon_class')->isRequired()->cannotBeEmpty()->end()
        ;

        return $treeBuilder;
    }
}
