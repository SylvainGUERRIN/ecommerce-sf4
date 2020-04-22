<?php

namespace App\Form;

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use App\Form\AvatarType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' =>'Votre nom',
                'attr' => ['placeholder' => 'Veuillez mettre votre nom']
            ])
            ->add('mail', EmailType::class, [
                'required' => true,
                'label' =>'Votre email',
                'attr' => ['placeholder' => 'Veuillez mettre votre email']
            ])
//            ->add('imageFile', VichImageType::class, [
//                'label' => 'Téléchargez une image pour mettre dans votre profil',
//                'required' => false,
//                'data_class' => null,
//                'attr' => ['placeholder' => 'choisir une image'],
//                'mapped' => true,
//            ])
//            ->add('avatarFile', FileType::class, [
//                'label' => 'Téléchargez une image pour mettre dans votre profil',
//                'required' => false,
//                'data_class' => null,
//                'attr' => ['placeholder' => 'choisir une image'],
//                'mapped' => true,
//            ])
//            ->add('avatar', EntityType::class, [
//                'class' => Avatar::class,
//                'label' => ' ',
//                'required' => false,
//                'mapped' => true
//            ])
//            ->add('avatar', AvatarType::class, [
//                'label' => ' ',
//                'required' => false,
//                'mapped' => true
//            ])
//            ->add('avatarFiles', FileType::class, [
//                'label' => 'Téléchargez une image pour mettre dans votre profil',
//                'required' => false,
//                'data_class' => null,
//                'mapped' => false,
//                'attr' => ['placeholder' => 'Choisir son avatar'],
////                'constraints' => [
////                    new Image([
////                        'maxSize' => '5M',
////                        'mimeTypes' => [
////                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
////                        ]
////                    ])
////                ]
//            ])
//            ->add('avatarurl', FileType::class, [
//                'label' => 'Téléchargez une image pour votre avatar',
//                'data_class' => null,
//                'required' => false,
//                'attr' => ['placeholder' => 'url image'],
//                'mapped' => false,
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '1M',
//                        'mimeTypes' => [
//                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
//                        ]
//                    ])
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
