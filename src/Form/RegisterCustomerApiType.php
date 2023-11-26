<?php

namespace App\Form;

use App\Entity\Countries;
use App\Entity\Customer;
use App\Entity\CustomersTypesRoles;
use App\Entity\GenderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RegisterCustomerApiType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer_type_role', EntityType::class, [
                'class'  => CustomersTypesRoles::class,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('gender_type', EntityType::class, [
                'class' => GenderType::class,
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('country_phone_code', EntityType::class, [
                'class' => Countries::class,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('cel_phone', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('date_of_birth', DateType::class, [
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
