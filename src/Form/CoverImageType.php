<?php

namespace App\Form;

use App\Entity\CoverImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoverImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('volanta', TextType::class, ['label' => 'Volanta', 'required' => false])
            ->add('title', TextareaType::class, ['label' => 'Título', 'required' => false])
            ->add('subtitle', TextareaType::class, ['label' => 'Subtítulo', 'required' => false,])
            ->add('nameBtn', TextType::class, ['label' => 'Nombre del Botón', 'required' => false,])
            ->add('linkBtn', TextType::class, ['label' => 'Vínculo del Botón', 'required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoverImage::class,
        ]);
    }
}
