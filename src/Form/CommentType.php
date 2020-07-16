<?php


namespace App\Form;


use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'required' => true,
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'Tapez votre nom']
            ])
            ->add('mail', EmailType::class,[
                'required' => true,
                'label' => 'Votre e-mail',
                'attr' => ['placeholder' => 'Tapez votre e-mail']
            ])
            ->add('content', TextareaType::class,[
                'required' => true,
                'label' => 'Votre commentaire',
                'attr' => ['placeholder' => 'Tapez votre commentaire ....']
            ])
//            ->add('comment_at')
//            ->add('valid')
//            ->add('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }

}
