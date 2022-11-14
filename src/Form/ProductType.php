<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subcategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Título', 'attr' => ['required' => true]])
            ->add('descriptionEs', TextareaType::class, ['label' => 'Descripción español', 'required' => false])
            ->add('descriptionEn', TextareaType::class, ['label' => 'Descripción Inglés', 'required' => false])
            ->add('category', EntityType::class, [
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría',
                'class'  => Category::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('brand', EntityType::class, [
                'placeholder' => 'Seleccione una marca',
                'label' => 'Marca',
                'class'  => Brand::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('model', TextType::class, ['label' => 'Modelo', 'attr' => ['required' => true]])
            ->add('weight', TextType::class, ['label' => 'Weight', 'attr' => ['required' => true]])
            ->add('cod', TextType::class, ['label' => 'Cod', 'attr' => ['style' => 'text-transform: uppercase', 'required' => false]])
            ->add('part_number', TextType::class, ['label' => 'Part number', 'attr' => ['style' => 'text-transform: uppercase', 'required' => true]])
            ->add('color', TextType::class, ['label' => 'Color', 'required' => false])
            ->add('screen_resolution', TextType::class, ['label' => 'Resolución de pantalla', 'required' => false])
            ->add('cpu', TextType::class, ['label' => 'CPU', 'required' => false])
            ->add('gpu', TextType::class, ['label' => 'GPU', 'required' => false])
            ->add('ram', TextType::class, ['label' => 'RAM', 'required' => false])
            ->add('memory', TextType::class, ['label' => 'Memoria', 'required' => false])
            ->add('screen_size', TextType::class, ['label' => 'Tamaño de pantalla', 'required' => false])
            ->add('op_sys', TextType::class, ['label' => 'S.O.', 'required' => false])
            ->add('conditium', TextType::class, ['label' => 'Condición', 'required' => false])
            ->add('onhand', NumberType::class, ['label' => 'Onhand', 'attr' => ['required' => true]])
            ->add('commited', NumberType::class, ['label' => 'Commited', 'attr' => ['required' => true]])
            ->add('incomming', NumberType::class, ['label' => 'Incomming', 'attr' => ['required' => true]])
            ->add('available', NumberType::class, ['label' => 'Available', 'attr' => ['required' => true]])

            ->add('image', FileType::class, [
                'label' => 'Imagen ',
                'multiple' => true,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new All([
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
                    ]),
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
