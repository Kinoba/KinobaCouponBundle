<?php

namespace Kinoba\CouponBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class KinobaCouponExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('kinoba_coupon.default_mask', $config['default_mask']);
        $container->setParameter('kinoba_coupon.characters', $config['characters']);
        $container->setParameter('kinoba_coupon.coupon_class', $config['coupon_class']);
        $container->setParameter('kinoba_coupon.generated_coupon_class', $config['generated_coupon_class']);
    }
    
    public function prepend(ContainerBuilder $container): void
    {
        $file = __DIR__.'/../Resources/config/stof_doctrine_extensions.yaml';
        
        try {
            $config = Yaml::parse(
                file_get_contents($file)
            );
        } catch (ParseException $e) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $file), 0, $e);
        }
        $container->prependExtensionConfig('stof_doctrine_extensions', $config['stof_doctrine_extensions']);
    }
}
