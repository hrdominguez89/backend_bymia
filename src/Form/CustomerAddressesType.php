<?php

namespace App\Form;

use App\Entity\Countries;
use App\Entity\CustomerAddresses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                'disabled' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('city', ChoiceType::class, [
                'placeholder' => 'Seleccione una ciudad',
                'label' => 'Ciudad',
                'disabled' => true,
                'mapped' => false,
                'required' => false,
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
            ->add('home_address', CheckboxType::class, [
                'label'    => 'Dirección predeterminada de envío',
                'required' => false,
            ])
            ->add('billing_address', CheckboxType::class, [
                'label'    => 'Dirección predeterminada de facturación',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomerAddresses::class,
        ]);
    }
}
