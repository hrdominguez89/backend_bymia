<?php

namespace App\Form;

use App\Entity\Countries;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // name
        // country_id
        // phone
        // email
        // message

        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('country_id', EntityType::class, [
                'class'  => Countries::class,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ])
            ->add('phone', TextType::class, [
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
            ->add('message', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
