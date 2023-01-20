<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                'label' => 'Inventario *',
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('name', TextType::class, ['label' => 'Nombre *', 'attr' => ['required' => true]])
            ->add('descriptionEs', TextareaType::class, ['label' => 'Descripción español *', 'required' => true])
            ->add('descriptionEn', TextareaType::class, ['label' => 'Descripción Inglés', 'required' => false])

            ->add('category', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría *',
                'choice_label' => function ($category, $key, $index) {
                    return $category->getName() . ' - ' . $category->getNomenclature();
                },
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
                'label' => 'Marca *',
                'choice_label' => function ($brand, $key, $index) {
                    return $brand->getName() . ' - ' . $brand->getNomenclature();
                },
                'required' => true,
            ])
            ->add('model', TextType::class, [
                'label' => 'Modelo *',
                'required' => true,
                'attr' => ["placeholder" => "Campo requerido", "minlength" => 6, 'style' => 'text-transform:uppercase', 'pattern' => '^[A-Za-z0-9]{6,}$', 'title' => 'Este campo debe contener 6 caracteres como mínimo sin espacios ni guiones'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                    ])
                ]
            ])
            ->add('color', TextType::class, [
                'label' => 'Color *',
                'required' => true,
                'attr' => ["placeholder" => "Campo requerido", "minlength" => 2, "maxlength" => 2, 'style' => 'text-transform:uppercase', 'pattern' => '^[A-Za-z0-9]{2}$', 'title' => 'Este campo debe contener 2 caracteres sin espacios ni guiones'],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 2,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{2}$/",
                        'message' => 'El valor debe cumplir con el formato "XX"',
                    ]),
                ]
            ])
            ->add('vp1', TextType::class, [
                'label' => 'Variable de producto 1 *',
                'mapped' => false,
                'required' => true,
                'attr' => ["placeholder" => "Campo requerido", "minlength" => 3, "maxlength" => 3, 'style' => 'text-transform:uppercase', 'pattern' => '^[A-Za-z0-9]{3}$', 'title' => 'Este campo debe contener 3 caracteres sin espacios ni guiones'],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{3}$/",
                        'message' => 'El valor debe cumplir con el formato "XXX"',
                    ]),
                ]
            ])
            ->add('vp2', TextType::class, [
                'label' => 'Variable de producto 2',
                'mapped' => false,
                'required' => false,
                'attr' => ["placeholder" => "Campo opcional", "disabled" => true, "maxlength" => 3, 'style' => 'text-transform:uppercase', 'pattern' => '^[A-Za-z0-9]{0-3}$', 'title' => 'Este campo puede estar vacio ó debe contener 3 caracteres sin espacios ni guiones'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{0,3}$/",
                        'message' => 'El valor debe cumplir con el formato "XXX"',
                    ]),
                ]
            ])
            ->add('vp3', TextType::class, [
                'label' => 'Variable de producto 3',
                'mapped' => false,
                'required' => false,
                'attr' => ["placeholder" => "Campo opcional", "disabled" => true, "maxlength" => 3, 'style' => 'text-transform:uppercase', 'pattern' => '^[A-Za-z0-9]{0-3}$', 'title' => 'Este campo puede estar vacio ó debe contener 3 caracteres sin espacios ni guiones'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{0,3}$/",
                        'message' => 'El valor debe cumplir con el formato "XXX"',
                    ]),
                ]
            ])
            ->add('sku', TextType::class, [
                'label' => 'SKU',
                'attr' => ['readonly' => 'readonly', "minlength" => 20, "maxlength" => 28, 'class' => 'text-center', 'style' => 'text-transform:uppercase'],
                'constraints' => [
                    new Length([
                        'min' => 20,
                        'max' => 28,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{2}-[A-Za-z0-9]{3}-[A-Za-z0-9]{6}-[A-Za-z0-9]{2}-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3})?)?$/",
                        'message' => 'El valor debe cumplir con el formato "CA-MAR-MOD31O-WH-8GB-128-19P"',
                    ]),
                ]
            ])


            ->add('cost', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'Costo *',
                'required' => true,
                'attr' => ['placeholder' => '0.00', 'pattern' => '^\d+(\.\d{1,2}|,\d{1,2})?$', 'title' => 'El formato debe ser 0,00 o 0.00'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+(\.\d{1,2}|,\d{1,2})?$/",
                        'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                    ]),
                ]
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'Precio *',
                'required' => true,
                'attr' => ['placeholder' => '0.00', 'pattern' => '^\d+(\.\d{1,2}|,\d{1,2})?$', 'title' => 'El formato debe ser 0,00 o 0.00'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+(\.\d{1,2}|,\d{1,2})?$/",
                        'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                    ]),
                ]
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Peso en Lb',
                'required' => false,
                'attr' => ['placeholder' => '0.00', 'pattern' => '^\d+(\.\d{0,3}|,\d{0,2})?$', 'title' => 'El formato debe ser 0.1 o 0.12 o 0.12 o 0,1 o 0,1 o 0,12 o 1'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+(\.\d{0,2}|,\d{0,2})?$/",
                        'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                    ]),
                ]
            ])

            ->add('cod', TextType::class, ['label' => 'Cod', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('part_number', TextType::class, ['label' => 'Part number', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('screen_resolution', TextType::class, ['label' => 'Resolución de pantalla', 'required' => false])
            ->add('cpu', TextType::class, ['label' => 'CPU', 'required' => false])
            ->add('gpu', TextType::class, ['label' => 'GPU', 'required' => false])
            ->add('ram', TextType::class, ['label' => 'RAM', 'required' => false])
            ->add('memory', TextType::class, ['label' => 'Memoria externa', 'required' => false])
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
