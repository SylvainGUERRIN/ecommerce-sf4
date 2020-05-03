<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Titre de la catégorie",
                'attr' => ['placeholder' => "Mettez un titre à la catégorie"]
            ])
//            ->add('slug', TextType::class, [
//                'label' => "L'url de la catégorie",
//                'attr' => ['placeholder' => "Ce champ n'est pas obligatoire.
//                L'url se met automatiquement sauf si vous voulez la personalisée"],
//                'required' => false
//            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de la catégorie",
                'attr' => ['placeholder' => "Mettez une description de la catégorie"],
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
