<?php

namespace Kinoba\CouponBundle\Form;

use Kinoba\CouponBundle\Model\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;

class CouponType extends AbstractType
{
    /**
     * @var string
     */
    private $defaultMask;

    public function __construct($defaultMask = null)
    {
        $this->defaultMask = $defaultMask;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mask', Type\TextType::class, array(
                'required' => true,
                'mapped' => false,
                'data' => $this->defaultMask,
            ))
            ->add('code', Type\TextType::class, array(
                'required' => true,
            ))
            ->add('amount', Type\TextType::class, array(
                'required' => true
            ))
            ->add('type', Type\ChoiceType::class, array(
                'required' => true,
                'choices' => Coupon::getTypes(),
                'choice_label' => function ($choiceValue, $key, $value) {
                    return "form.coupon.types.$key";
                },
                'choice_translation_domain' => 'KinobaCouponBundle',
            ))
            ->add('active', Type\CheckboxType::class, array(
                'required' => false
            ))
            ->add('maxNumber', Type\TextType::class, array(
                'required' => false
            ))
            ->add('expiredAt', Type\DateTimeType::class, array(
                'required' => false
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'translation_domain' => 'KinobaCouponBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coupon_type';
    }
}
