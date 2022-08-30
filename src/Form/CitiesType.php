<?php

namespace App\Form;

use App\Entity\Cities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre de la Ciudad', 'required' => true])
            ->add('latitude', TextType::class, ['label' => 'Latitud', 'required' => false])
            ->add('longitude', TextType::class, ['label' => 'Longitud', 'required' => false])
            // ->add('state_code')
            // ->add('country_code')
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('flag')
            // ->add('wikiDataId')
            // ->add('state')
            // ->add('country')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cities::class,
        ]);
    }
}
