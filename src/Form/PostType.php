<?php


namespace App\Form;


use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => ['placeholder' => "Mettez le titre de l'article"]
            ])
            ->add('imageFile',  FileType::class, [
                'label' => 'Téléchargez une image pour votre article',
                'data_class' => null,
                'required' => false,
                'attr' => ['placeholder' => 'Choisir une image'],
                'mapped' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
                        ]
                    ])
                ]
            ])
            ->add('content', TextareaType::class,[
                'label' => "Mettre le contenu textuel de votre article",
            ])
            ->add('excerpt', TextType::class, [
                'label' => "Extrait de la description de l'article / texte d'accroche",
                'attr' => ['placeholder' => "Mettez un extrait (il ne doit pas dépasser 255 caractères) de l'article."],
            ])
            ->add('refDescription', TextType::class,[
                'label' => "Mettre une bref description pour le référencement",
                'attr' => ['placeholder' => "Mettez une description qui ne doit pas dépasser 255 caractères."],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }

}
