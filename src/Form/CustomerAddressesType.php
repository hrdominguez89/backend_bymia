<?php

namespace App\Form;

use App\Entity\Countries;
use App\Entity\CustomerAddresses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerAddressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', EntityType::class, [
                'placeholder' => 'Seleccione un país',
                'label' => 'País',
                'class'  => Countries::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('state', ChoiceType::class, [
                'placeholder' => 'Seleccione un estado/provincia',
                'label' => 'Estado/Provincia',
                'choices'  => [
                    'Estado/Provincia' => null,
                ],
                'mapped'=>false,
                'required' => true,
            ])
            ->add('city', ChoiceType::class, [
                'placeholder' => 'Seleccione una ciudad',
                'label' => 'Ciudad',
                'choices'  => [
                    'Ciudad' => null,
                ],
                'mapped'=>false,
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'label' => 'Dirección',
                'required' => true
            ])
            ->add('number_street', TextType::class, [
                'label' => 'Número',
                'required' => true
            ])
            ->add('floor', TextType::class, [
                'label' => 'Piso',
                'required' => false
            ])
            ->add('department', TextType::class, [
                'label' => 'Departamento',
                'required' => false
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Código postal',
                'required' => true
            ])
            ->add('additional_info', TextareaType::class, [
                'label' => 'Información adicional',
                'required' => false
            ])
            ->add('favorite_address', ChoiceType::class, [
                'choices' => [
                    'Dirección predeterminada de envío' => true,
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false
            ])
            ->add('billing_address', ChoiceType::class, [
                'choices'  => [
                    'Dirección predeterminada de facturación' => true,
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAddresses::class,
        ]);
    }
}
