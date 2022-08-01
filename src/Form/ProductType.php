<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parentId')
            ->add('sku')
            ->add('badges')
            ->add('availability')
            ->add('name')
            ->add('slug')
            ->add('image')
            ->add('description')
            ->add('stock')
            ->add('url')
            ->add('weight')
            ->add('price')
            ->add('offerPrice')
            ->add('offerStartDate')
            ->add('offerEndDate')
            ->add('htmlDescription')
            ->add('shortDescription')
            ->add('color')
            ->add('length')
            ->add('dimensions')
            ->add('date')
            ->add('featured')
            ->add('sales')
            ->add('reviews')
            ->add('rating')
//            ->add('brandId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
