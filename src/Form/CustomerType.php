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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Imagen ',

                // unmapped means that this field is not associated to any entity property
                // 'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('customer_type_role', EntityType::class, [
                'placeholder' => 'Seleccione un tipo de cliente',
                'label' => 'Tipo de cliente',
                'class'  => CustomersTypesRoles::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('name', TextType::class, ['label' => 'Nombres/Razon Social', 'required' => true])
            ->add('date_of_birth', DateType::class, ['label' => 'Fecha de nacimiento', 'required' => false, 'widget' => 'single_text'])
            ->add('gender_type', EntityType::class, [
                'placeholder' => 'Seleccione un género',
                'label' => 'Género',
                'class'  => GenderType::class,
                'choice_label' => 'description',
                'required' => false,
            ])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => true])
            ->add('url_facebook', UrlType::class, ['label' => 'Facebook', 'required' => false])
            ->add('url_instagram', UrlType::class, ['label' => 'Instagram', 'required' => false])
            ->add('country_phone_code', EntityType::class, [
                'placeholder' => 'Seleccione un país',
                'label' => 'Cód. de país',
                'class'  => Countries::class,
                'choice_label' => function ($countries) {
                    return $countries->getName() . ' (' . $countries->getPhonecode().')';
                },
                'required' => true
            ])
            ->add('cel_phone', TextType::class, ['label' => 'Nro.', 'required' => true])
            ->add('phone', TextType::class, ['label' => 'Nro.', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
