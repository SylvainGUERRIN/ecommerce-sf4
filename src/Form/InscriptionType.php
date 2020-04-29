<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' =>'Votre prénom:',
                'attr' => ['placeholder' => 'Veuillez mettre votre nom']
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' =>'Votre nom de famille:',
                'attr' => ['placeholder' => 'Veuillez mettre votre nom']
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' =>'Votre email',
                'attr' => ['placeholder' => 'Veuillez mettre votre email']
            ])
            ->add('pass', RepeatedType::class, [
                'label' =>'Votre mot de passe:',
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => false, 'attr' => [
                    'placeholder' => 'Confirmer le mot de passe']],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
