<?php


namespace App\Form;


use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Tva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du produit",
                'attr' => ['placeholder' => "Mettez le nom du produit"]
            ])
//            ->add('slug', TextType::class, [
//                'label' => "L'url de l'article",
//                'attr' => ['placeholder' => "Ce champ n'est pas obligatoire.
//                L'url se met automatiquement sauf si vous voulez la personalisée"],
//                'required' => false
//            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du produit",
                'attr' => ['placeholder' => "Mettez une description de votre produit la plus compléte possible pour vos clients."],
            ])
            ->add('dispo', CheckboxType::class, [
                'label' => "Disponibilité du produit",
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false
            ])
            ->add('quantity', NumberType::class, [
                'label' => "Quantité en stock",
                'required' => true
            ])
//            ->add('imageFile',  FileType::class, [
//                'label' => 'Téléchargez une image pour votre article',
//                'data_class' => null,
//                'required' => false,
//                'attr' => ['placeholder' => 'Choisir une image'],
//                'mapped' => true,
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '5M',
//                        'mimeTypes' => [
//                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
//                        ]
//                    ])
//                ]
//            ])
            ->add('productImage', ProductImageType::class, [
                'label' => false,
                'required' => false,
                'mapped' => true
            ])
            ->add('category', EntityType::class, [
                'label' => "Choississez la catégorie correspondant au produit",
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                //'multiple' => true
            ])
            ->add('tva', EntityType::class, [
                'label' => "Choississez la tva correspondant au produit",
                'required' => false,
                'class' => Tva::class,
                'choice_label' => 'name',
                //'multiple' => true
            ])
            ->add('price', NumberType::class, [
                'label' => "Prix du produit",
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
