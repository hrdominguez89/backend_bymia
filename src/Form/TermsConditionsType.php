<?php

namespace App\Form;

use App\Entity\TermsConditions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermsConditionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Descripción'
            ])
            ->add('save', SubmitType::class, ['label' => 'Guardar', 'attr'=>['class'=>'mt-2 btn btn-primary']])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TermsConditions::class,
        ]);
    }
}
