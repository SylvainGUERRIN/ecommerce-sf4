<?php


namespace App\Form;


use App\Entity\Tva;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TvaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('multiplicate', NumberType::class, [
                'label' => "Multiplicateur pour cette TVA",
                'required' => true
            ])
            ->add('name', TextType::class, [
                'label' => "Nom de la TVA",
                'required' => true
            ])
            ->add('value', TextType::class, [
                'label' => "Valeur de la TVA",
                'required' => true
            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tva::class,
        ]);
    }

}
