<?php

namespace App\Form;

use App\Entity\States;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Nombre del Estado/Provincia', 'required' => true])
            ->add('latitude',TextType::class, ['label' => 'Latitud', 'required' => false])
            ->add('longitude',TextType::class, ['label' => 'Longitud', 'required' => false])
            // ->add('country_code')
            // ->add('fips_code')
            // ->add('iso2')
            // ->add('type')
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('flag')
            // ->add('wikiDataId')
            // ->add('country')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => States::class,
        ]);
    }
}
