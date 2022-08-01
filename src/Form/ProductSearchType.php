<?php

namespace App\Form;

use App\Form\Model\ProductSearchDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre', 'required' => false])
            ->add('category', TextType::class, ['label' => 'Categoría', 'required' => false])
            ->add('subcategory', TextType::class, ['label' => 'Sub Categoría', 'required' => false])
            ->add('state',ChoiceType::class, [
                'choices'  => [
                    'Todas'=>'all',
                    'En existencia'=>'in-stock',
                    'Agotadas'=>'no-stock'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSearchDto::class,
        ]);
    }
}
