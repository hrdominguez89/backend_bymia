<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SectionsHome;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionsHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleSection1', TextType::class, [
                'label' => 'Título de la 1ra sección',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba el título de la 1ra sección de la Home.']
            ])

            ->add('tagSection1', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.visible = true')
                        ->andwhere('t.principal = true')
                        ->orderBy('t.name');
                },
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta de la 1ra sección',
                'choice_label' => 'name',
                'required' => true,
            ])

            ->add('category1Section1', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '1ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category2Section1', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '2da. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category3Section1', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '3ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('titleSection2', TextType::class, [
                'label' => 'Título de la 2da sección',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba el título de la 2da sección de la Home.']
            ])

            ->add('tagSection2', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.visible = true')
                        ->andwhere('t.principal = true')
                        ->orderBy('t.name');
                },
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta de la 2da sección',
                'choice_label' => 'name',
                'required' => true,
            ])

            ->add('category1Section2', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '1ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category2Section2', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '2da. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category3Section2', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '3ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])


            ->add('titleSection3', TextType::class, [
                'label' => 'Título de la 3ra sección',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba el título de la 3ra sección de la Home.']
            ])

            ->add('tagSection3', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.visible = true')
                        ->andwhere('t.principal = true')
                        ->orderBy('t.name');
                },
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta de la 3ra sección',
                'choice_label' => 'name',
                'required' => true,
            ])


            ->add('category1Section3', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '1ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category2Section3', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '2da. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category3Section3', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '3ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('titleSection4', TextType::class, [
                'label' => 'Título de la 4ta sección',
                'required' => true,
                'attr' => ['placeholder' => 'Escriba el título de la 4ta sección de la Home.']
            ])

            ->add('tagSection4', EntityType::class, [
                'class'  => Tag::class,
                'query_builder' => function (TagRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.visible = true')
                        ->andwhere('t.principal = true')
                        ->orderBy('t.name');
                },
                'placeholder' => 'Seleccione una etiqueta.',
                'label' => 'Etiqueta de la 4ta sección',
                'choice_label' => 'name',
                'required' => true,
            ])


            ->add('category1Section4', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '1ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category2Section4', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '2da. categoría',
                'choice_label' => 'name',
                'required' => false,
            ])

            ->add('category3Section4', EntityType::class, [
                'class'  => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id3pl is not null')
                        ->andWhere('c.visible = true')
                        ->andWhere('c.principal = true')
                        ->orderBy('c.name');
                },
                'placeholder' => 'Seleccione una categoría.',
                'label' => '3ra. categoría',
                'choice_label' => 'name',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionsHome::class,
        ]);
    }
}
