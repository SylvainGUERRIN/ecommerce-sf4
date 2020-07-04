<?php


namespace App\Form;


use App\Entity\UserCommands;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SentUserCommandType extends AbstractType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sent',CheckboxType::class, [
                'label' => 'Confirmation de l\'envoi de la commande.',
                'required' => true,
            ])
            ->add('sentAt', TextType::class, [
                'label' => 'Date d\'envoi du colis.',
                'required' => true,
            ])
        ;
        $builder->get('sentAt')->addModelTransformer($this->transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserCommands::class,
        ]);
    }

}
