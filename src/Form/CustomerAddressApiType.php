<?php

namespace App\Form;

use App\Entity\CustomerAddresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer')
            ->add('country')
            ->add('state')
            ->add('street')
            ->add('city')
            ->add('number_street')
            ->add('floor')
            ->add('department')
            ->add('postal_code')
            ->add('additional_info')
            ->add('favorite_address')
            ->add('billing_address')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAddresses::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
