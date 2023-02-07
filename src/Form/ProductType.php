<?php

namespace App\Form;

use App\Constants\Constants;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Entity\Specification;
use App\Entity\Tag;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\SpecificationRepository;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('descriptionEs', TextareaType::class, ['label' => 'Descripción corta español *', 'required' => true, 'attr' => ['rows' => 4]])
            ->add('long_description_es', TextareaType::class, ['label' => 'Descripción larga en español', 'required' => false, 'attr' => ['rows' => 4]])
            ->add('descriptionEn', TextareaType::class, ['label' => 'Descripción corta Inglés', 'required' => false, 'attr' => ['rows' => 4]])
            ->add('long_description_en', TextareaType::class, ['label' => 'Descripción larga en Inglés', 'required' => false, 'attr' => ['rows' => 4]])

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
            ->add('model', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $br) {
                    return $br->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_MODEL)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione un modelo.',
                'label' => 'Modelo *',
                'required' => true,
                'choice_label' => 'name',
            ])
            ->add('color', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $br) {
                    return $br->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_COLOR)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione Color.',
                'label' => 'Color *',
                'required' => true,
                'choice_label' => 'name',
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
                'attr' => ['readonly' => 'readonly', "minlength" => 28, "maxlength" => 36, 'class' => 'text-center', 'style' => 'text-transform:uppercase'],
                'constraints' => [
                    new Length([
                        'min' => 28,
                        'max' => 36,
                        'minMessage' => 'El campo debe tener al menos {{ limit }} caracteres',
                        'maxMessage' => 'El campo no debe tener más de {{ limit }} caracteres',
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-z0-9]{3}-[A-Za-z0-9]{3}-[A-Za-z0-9]{12}-[A-Za-z0-9]{3}-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3}(?:-[A-Za-z0-9]{3})?)?$/",
                        'message' => 'El valor debe cumplir con el formato "CAT-MAR-000000MOD31O-COL-VP1[-VP2-VP3] (VP2 y VP3 son opcionales."',
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
                'label' => 'Peso en Lb *',
                'required' => true,
                'attr' => ['placeholder' => '0.00', 'pattern' => '^\d+(\.\d{0,3}|,\d{0,2})?$', 'title' => 'El formato debe ser 0.1 o 0.12 o 0.12 o 0,1 o 0,1 o 0,12 o 1'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+(\.\d{0,3}|,\d{0,2})?$/",
                        'message' => 'El valor debe cumplir con el formato 00,00 o 00.00',
                    ]),
                ]
            ])

            ->add('cod', TextType::class, ['label' => 'Cod.', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('part_number', TextType::class, ['label' => 'Part number', 'required' => false, 'attr' => ['style' => 'text-transform: uppercase']])
            ->add('screen_resolution', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SCREEN_RESOLUTION)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de resolución de pantalla.',
                'label' => 'Resolución de pantalla',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('cpu', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $cpu) {
                    return $cpu->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_CPU)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de CPU.',
                'label' => 'CPU',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('gpu', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $gpu) {
                    return $gpu->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_GPU)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo de GPU.',
                'label' => 'GPU',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('memory', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $mem) {
                    return $mem->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_MEMORY)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tipo memoria.',
                'label' => 'Memoria',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('storage', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $sto) {
                    return $sto->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_STORAGE)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tamaño de almacenamiento.',
                'label' => 'Tamaño (Capacidad de almacenamiento)',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('screen_size', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $ss) {
                    return $ss->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SCREEN_SIZE)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione tamaño de pantalla.',
                'label' => 'Tamaño de pantalla',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('op_sys', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $os) {
                    return $os->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_SO)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione sistema operativo.',
                'label' => 'O.S.',
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('conditium', EntityType::class, [
                'class'  => Specification::class,
                'query_builder' => function (SpecificationRepository $con) {
                    return $con->createQueryBuilder('s')
                        ->where('st.id = :id')
                        ->setParameter('id', Constants::SPECIFICATION_CONDITUM)
                        ->leftJoin('App:SpecificationTypes', 'st', 'WITH', 's.specification_type = st.id')
                        ->orderBy('s.name');
                },
                'placeholder' => 'Seleccione condición del producto.',
                'label' => 'Condición *',
                'required' => true,
                'choice_label' => 'name',
            ])

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
