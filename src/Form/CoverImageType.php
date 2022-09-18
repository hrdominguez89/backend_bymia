<?php

namespace App\Form;

use App\Entity\CoverImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CoverImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pre_title', TextType::class, ['label' => 'Pre-Título', 'required' => false])
            ->add('title', TextareaType::class, ['label' => 'Título', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'Descripción', 'required' => false,])
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
