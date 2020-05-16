<?php


namespace App\Form;


use App\Entity\Promo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la promotion",
                'required' => true
            ])
            ->add('percent', NumberType::class, [
                'label' => "Pourcentage de réduction pour cette promotion",
                'attr' => ['placeholder' => "Mettre le chiffre uniquement"],
                'required' => true
            ])
            ->add('activated', CheckboxType::class, [
                'label' => "Activation de la promotion",
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false
            ])
            ->add('code', TextType::class, [
                'label' => "code que l'utilisateur doit rentrer pour activer la promotion",
                'attr' => ['placeholder' => "Ce code est facultatif, si vous appliquez cette promotion à un produit"],
                'required' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promo::class,
        ]);
    }

}
