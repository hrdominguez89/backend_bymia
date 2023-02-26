<?php

namespace App\Form;

use App\Entity\BrandsSections;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BrandsSectionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brandName1', TextType::class, [
                'label' => 'Nombre marca 1',
                'required' => true,
            ])
            ->add('brandImage1', FileType::class, [
                'label' => 'Imagen marca 1',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('brandName2', TextType::class, [
                'label' => 'Nombre marca 2',
                'required' => true,
            ])
            ->add('brandImage2', FileType::class, [
                'label' => 'Imagen marca 2',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('brandName3', TextType::class, [
                'label' => 'Nombre marca 3',
                'required' => true,
            ])
            ->add('brandImage3', FileType::class, [
                'label' => 'Imagen marca 3',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('brandName4', TextType::class, [
                'label' => 'Nombre marca 4',
                'required' => true,
            ])
            ->add('brandImage4', FileType::class, [
                'label' => 'Imagen marca 4',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('brandName5', TextType::class, [
                'label' => 'Nombre marca 5',
                'required' => true,
            ])
            ->add('brandImage5', FileType::class, [
                'label' => 'Imagen marca 5',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('brandName6', TextType::class, [
                'label' => 'Nombre marca 6',
                'required' => true,
            ])
            ->add('brandImage6', FileType::class, [
                'label' => 'Imagen marca 6',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BrandsSections::class,
        ]);
    }
}
