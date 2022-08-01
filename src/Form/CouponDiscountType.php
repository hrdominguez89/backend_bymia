<?php

namespace App\Form;

use App\Entity\CouponDiscount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('percent',null,[
                'label' => 'Porciento?'
            ])
            ->add('numberOfUses',null,[
                'required' => true,
                'label' => 'Cantidad de usos'
            ])
            ->add('nro',null,[
                'label' => 'Número'
            ])
            ->add('value',null,[
                'label' => 'Valor'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CouponDiscount::class,
        ]);
    }
}
