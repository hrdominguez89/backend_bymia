<?php

namespace App\Form;

use App\Entity\ApiClients;
use App\Entity\ApiClientsTypesRoles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ApiClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre cliente API', 'required' => true])
            ->add('api_client_type_role', EntityType::class, [
                'placeholder' => 'Seleccione un rol',
                'label' => 'Rol',
                'class'  => ApiClientsTypesRoles::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            
            ->add('reset_api_key', CheckboxType::class, [
                'label'    => 'Reiniciar API KEY',
                'required' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiClients::class,
        ]);
    }
}
