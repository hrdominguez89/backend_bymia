<?php

namespace App\Form;

use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre', 'attr' => ['style' => 'text-transform: uppercase', 'required' => true]])
            ->add('image', FileType::class, [
                'label' => 'Imagen ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('descriptionEs', TextareaType::class, ['label' => 'Descripción español', 'required' => false])
            ->add('descriptionEn', TextareaType::class, ['label' => 'Descripción Inglés', 'required' => false])
            ->add('nomenclature', TextType::class, ['label' => 'Nomenclatura', 'attr' => ['style' => 'text-transform: uppercase', 'minlength' => 3, 'maxlength' => 3]])
            ->add('principal', CheckboxType::class, [
                'label'    => 'Establecer como marca principal',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
