<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SubcategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre de subcategoría', 'attr' => ['style' => 'text-transform:uppercase', 'required' => true,'placeholder'=>'Escriba el nombre de la subcategoría']])
            ->add('category', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría',
                'label' => 'Categoría',
                'choice_label' => 'name',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subcategory::class,
        ]);
    }
}
