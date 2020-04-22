<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'required' => true,
                'label' =>'Votre mot de passe actuel:',
                'attr' => ['placeholder' => 'Veuillez mettre votre mot de passe actuel']
            ])
            ->add('newPassword', PasswordType::class, [
                'required' => true,
                'label' =>'Votre nouveau mot de passe:',
                'attr' => ['placeholder' => 'Veuillez mettre votre nouveau mot de passe']
            ])
            ->add('confirmPassword', PasswordType::class, [
                'required' => true,
                'label' =>'Confirmez votre nouveau mot de passe:',
                'attr' => ['placeholder' => 'Veuillez confirmer votre nouveau mot de passe']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
