<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Entity\Subcategory;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('inventory', EntityType::class, [
                'class'  => Inventory::class,
                'placeholder' => 'Seleccione un inventario',
                'label' => 'Inventario',
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('name', TextType::class, ['label' => 'Nombre', 'attr' => ['required' => true]])
            ->add('descriptionEs', TextareaType::class, ['label' => 'Descripción español', 'required' => false])
            ->add('descriptionEn', TextareaType::class, ['label' => 'Descripción Inglés', 'required' => false])
            ->add('cost', NumberType::class, ['label' => 'Costo', 'required' => true])
            ->add('price', NumberType::class, ['label' => 'Precio', 'required' => true])

            ->add('category', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría',
                'choice_label' => 'nomenclature',
                'required' => true,
            ])
            ->add('subcategory', ChoiceType::class, [
                'placeholder' => 'Seleccione una subcategoría',
                'label' => 'Subcategoría',
                'disabled' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('brand', EntityType::class, [
                'class'  => Brand::class,
                'query_builder' => function (BrandRepository $br) {
                    return $br->createQueryBuilder('b')
                        ->where('b.id3pl is not null')
                        ->orderBy('b.name');
                },
                'placeholder' => 'Seleccione una marca',
                'label' => 'Marca',
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('color', TextType::class, [
                'label' => 'Color',
                'required' => true,
                'attr' => ['required' => true, "minlength" => 2, "maxlength" => 2],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 2,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('vp1', TextType::class, [
                'label' => 'Variable de producto 1',
                'mapped' => false,
                'attr' => ['required' => true, "minlength" => 3, "maxlength" => 3],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('vp2', TextType::class, [
                'label' => 'Variable de producto 2',
                'mapped' => false,
                'attr' => ['required' => false, "disabled" => true, "minlength" => 3, "maxlength" => 3],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('vp3', TextType::class, [
                'label' => 'Variable de producto 3',
                'mapped' => false,
                'attr' => ['required' => false, "disabled" => true, "minlength" => 3, "maxlength" => 3],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'attr' => ['readonly' => 'readonly', "minlength" => 3, "maxlength" => 3],
                'constraints' => [
                    new Length([
                        'min' => 20,
                        'max' => 28,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{2}-[A-Za-z0-9]{3}-[A-Za-z0-9]{6}-[A-Za-z0-9]{2}-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3})?)?$/",
                        'message' => 'El valor debe cumplir con el formato "XXX-999"',
                    ]),
                ]
            ])
            ->add('model', TextType::class, [
                'label' => 'Modelo', 'attr' => ['required' => false, "minlength" => 6, "maxlength" => 6],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 6,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('weight', TextType::class, ['label' => 'Weight', 'attr' => ['required' => true]])
            ->add('cod', TextType::class, ['label' => 'Cod', 'attr' => ['style' => 'text-transform: uppercase', 'required' => false]])
            ->add('part_number', TextType::class, ['label' => 'Part number', 'attr' => ['style' => 'text-transform: uppercase', 'required' => false]])
            ->add('screen_resolution', TextType::class, ['label' => 'Resolución de pantalla', 'required' => false])
            ->add('cpu', TextType::class, ['label' => 'CPU', 'required' => false])
            ->add('gpu', TextType::class, ['label' => 'GPU', 'required' => false])
            ->add('ram', TextType::class, ['label' => 'RAM', 'required' => false])
            ->add('memory', TextType::class, ['label' => 'Memoria', 'required' => false])
            ->add('screen_size', TextType::class, ['label' => 'Tamaño de pantalla', 'required' => false])
            ->add('op_sys', TextType::class, ['label' => 'S.O.', 'required' => false])
            ->add('conditium', TextType::class, ['label' => 'Condición', 'required' => false])

            ->add('images', HiddenType::class, [
                'mapped' => false,
                'data' => [],
                'attr' => [
                    'data-type' => 'array'
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
