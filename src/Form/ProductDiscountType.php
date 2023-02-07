<?php

namespace App\Form;

use App\Entity\ProductDiscount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;


class ProductDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'label'    => 'Fecha de inicio',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('end_date', DateType::class, [
                'label'    => 'Fecha de fin',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('product_limit', NumberType::class, [
                'label' => 'Limite de productos',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba un numero entero', 'pattern' => '^-?\d+$', 'title' => 'El formato debe ser de numero entero.'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^-?\d+$/",
                        'message' => 'El valor debe cumplir con el formato de numero entero.',
                    ]),
                ]
            ])
            ->add('percentage_discount', NumberType::class, [
                'label' => 'Porcentaje de descuento',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba un numero entero', 'pattern' => '^(100|[1-9][0-9]|[1-9]0|[0-9])$', 'title' => 'El formato debe ser de numero entero.'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(100|[1-9][0-9]|[1-9]0|[0-9])$/",
                        'message' => 'El valor debe cumplir con el formato de numero entero.',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductDiscount::class,
        ]);
    }
}
