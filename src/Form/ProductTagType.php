<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tag', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $tag) {
                    return $tag->createQueryBuilder('t')
                        ->orderBy('t.name');
                },
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta',
                'required' => false,
                'choice_label' => 'name',
            ])

            ->add('tag_expires', CheckboxType::class, [
                'label'    => '¿La etiqueta expira?',
                'required' => false,
                'attr' => ['disabled' => true]
            ])

            ->add('tag_expiration_date', DateType::class, [
                'label'    => 'Fecha de expiración',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['disabled' => true]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
