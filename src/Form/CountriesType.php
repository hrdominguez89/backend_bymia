<?php

namespace App\Form;

use App\Entity\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre del país', 'required' => true])
            ->add('region', TextType::class, ['label' => 'Region', 'required' => false])
            ->add('subregion', TextType::class, ['label' => 'Subregión', 'required' => false])
            ->add('latitude', TextType::class, ['label' => 'Latitud', 'required' => false])
            ->add('longitude', TextType::class, ['label' => 'Longitud', 'required' => false])


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
