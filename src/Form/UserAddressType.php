<?php

namespace App\Form;

use App\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('firstname')
//            ->add('lastname')
            ->add('phone',NumberType::class, [
                'label' => 'Votre numéro de téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Mettre votre numéro de téléphone'
                ]
            ])
            ->add('address',TextType::class, [
                'label' => 'Votre adresse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'rue'
                ]
            ])
            ->add('cp',NumberType::class, [
                'label' => 'Votre code postal',
                'required' => true,
                'attr' => [
                    'placeholder' => 'code postal'
                ]
            ])
            ->add('town',TextType::class, [
                'label' => 'Votre ville',
                'required' => true,
                'attr' => [
                    'placeholder' => 'votre ville'
                ]
            ])
            ->add('country',TextType::class, [
                'label' => 'Votre pays',
                'required' => true,
                'attr' => [
                    'placeholder' => 'votre pays'
                ]
            ])
            ->add('complement',TextType::class, [
                'label' => 'Complément d\'adresse',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Si besoin d\'indications pour la livraison'
                ]
            ])
            ->add('for_command', CheckboxType::class, [
                'label' => 'Adresse pour la livraison',
                'required' => false,
            ])
            ->add('for_billing', CheckboxType::class, [
                'label' => 'Adresse pour la facturation',
                'required' => false,
            ])
//            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
        ]);
    }
}
