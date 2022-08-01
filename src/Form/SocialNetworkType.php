<?php

namespace App\Form;

use App\Entity\SocialNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',ChoiceType::class, [
                'choices'  => [
                     'Facebook'=>'fab fa-facebook fa-2x text-primary',
                     'Facebook Messenger'=>'fab fa-facebook-messenger fa-2x text-primary',
                     'WhatsApp'=>'fab fa-whatsapp fa-2x text-success',
                     'Instagram'=>'fab fa-instagram fa-2x text-primary',
                     'Skype'=>'fab fa-skype fa-2x text-info',
                     'Telegram'=>'fab fa-telegram fa-2x text-primary',
                     'LinkedIn'=>'fab fa-linkedin fa-2x text-primary',
                     'Twitter'=>'fab fa-twitter fa-2x text-primary'
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => true,
                'label' => 'Url'
            ])
            ->add('color',ColorType::class,[
                'required' => true,
                'label' => 'Color'
            ])
            ->add('type',HiddenType::class,[
                'required' => false,
                'label' => 'Tipo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SocialNetwork::class,
        ]);
    }
}
