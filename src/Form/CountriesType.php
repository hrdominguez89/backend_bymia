<?php

namespace App\Form;

use App\Entity\Countries;
use App\Entity\RegionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre del país', 'required' => true])
            ->add('latitude', TextType::class, ['label' => 'Latitud', 'required' => false])
            ->add('longitude', TextType::class, ['label' => 'Longitud', 'required' => false])
            ->add('region_type', EntityType::class, [
                'placeholder' => 'Seleccione una región',
                'label' => 'Región',
                'class'  => RegionType::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('subregion', ChoiceType::class, [
                'placeholder' => 'Seleccione una subregión',
                'label' => 'Subregión',
                'disabled' => true,
                'mapped' => false,
                'required' => true,
            ])

            // ->add('iso3')
            // ->add('numeric_code')
            // ->add('iso2')
            // ->add('phonecode')
            // ->add('capital')
            // ->add('currency')
            // ->add('currency_name')
            // ->add('currency_symbol')
            // ->add('tld')
            // ->add('native')
            // ->add('timezones')
            // ->add('translations')
            // ->add('emoji')
            // ->add('emojiU')
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('flag')
            // ->add('wikiDataId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Countries::class,
        ]);
    }
}
