<?php

namespace App\Form;

use App\Entity\Brand;
use App\Form\Model\ProductDto;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('sku', TextType::class)
            ->add('productId', TextType::class)
            ->add('productIdParent', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('stock', NumberType::class)
            ->add('url', TextType::class)

            ->add('brand',  TextType::class)

            ->add('weight', NumberType::class)
            ->add('price', NumberType::class)
            ->add('offerPrice', NumberType::class)

            ->add('offerStartDate', TextType::class)
            ->add('offerEndDate', TextType::class)

            ->add('htmlDescription', TextType::class)
            ->add('shortDescription', TextType::class)
            ->add('color', TextType::class)
            ->add('length', NumberType::class)
            ->add('dimentions', TextType::class)
            ->add('destacado', TextType::class)
            ->add('sales', TextType::class);

        // $builder->get('id')->addModelTransformer(new CallbackTransformer(
        //     function ($id) {
        //         if ($id === null) {
        //             return '';
        //         }
        //         return $id->toString();
        //     },
        //     function ($id) {
        //         return $id === null ? null : $id;
        //     }
        // ));

        // $builder->get('brand')->addModelTransformer(new CallbackTransformer(
        //     function ($brand) {
        //         if ($brand === null) {
        //             return '';
        //         }
        //         return $brand->toString();
        //     },
        //     function ($brand) {
        //         return $brand === null ? null : $brand;
        //     }
        // ));

        $builder->get('offerStartDate')->addModelTransformer(new CallbackTransformer(
            function ($offerStartDate) {
                if ($offerStartDate === null) {
                    return null;
                }
                return $offerStartDate;
            },
            function ($offerStartDate) {
                return $offerStartDate === null ? null : new \DateTime($offerStartDate);
            }
        ));

        $builder->get('offerEndDate')->addModelTransformer(new CallbackTransformer(
            function ($offerEndDate) {
                if ($offerEndDate === null) {
                    return null;
                }
                return $offerEndDate;
            },
            function ($offerEndDate) {
                return $offerEndDate === null ? null : new \DateTime($offerEndDate);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDto::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}
