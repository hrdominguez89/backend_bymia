<?php

namespace App\Form;

use App\Entity\ContactUs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Descripcción'
            ])
            ->add('address', TextareaType::class, [
                'required' => true,
                'label' => 'Dirección'
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Correo electrónico'
            ])
            ->add('phoneMain', TelType::class, [
                'required' => true,
                'label' => 'Teléfono principal'
            ])
            ->add('phoneOther', TelType::class, [
                'required' => false,
                'label' => 'Otro teléfono'
            ])
            ->add('url', UrlType::class, [
                'required' => false,
                'label' => 'Url'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactUs::class,
        ]);
    }
}
