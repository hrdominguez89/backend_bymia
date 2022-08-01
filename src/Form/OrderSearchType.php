<?php

namespace App\Form;

use App\Entity\Customer;
use App\Form\Model\OrderSearchDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, ['label' => 'Nro. Orden', 'required' => false])
            ->add('customer', TextType::class, ['label' => 'Cliente', 'required' => false])
            ->add('status', ChoiceType::class, array(
                //'choice_label' => 'Estado',
                'required' => false,
                'empty_data' => '',
                'choices'  => array(
                    '' => '',
                    'PROCESANDO' => 'Pocesando',
                    'COMPLETADO' => 'Completado',
                    'CANCELADO' => 'Cancelado',
                    'REEMBOLSADO' => 'Reembolsado',
                    'FALLIDO' => 'Fallido',
                ),
                // *this line is important*
                //'choice_value' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderSearchDto::class,
        ]);
    }
}
