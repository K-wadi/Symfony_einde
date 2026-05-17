<?php

namespace App\Form;

use App\Entity\Pizza;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form opties (opdracht forms 1.4 + presentatie custom forms).
 */
class PizzaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Pizzanaam',
                'help' => 'Gebruik een herkenbare naam, bijv. Margherita.',
                'data' => 'Margherita', // vooraf ingevulde data (opdracht 1.4)
                'attr' => ['class' => 'form-control form-control-lg'],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Verkoopprijs',
                'currency' => 'EUR',
                'help' => 'Prijs inclusief BTW.',
            ])
            ->add('size', ChoiceType::class, [
                'label' => 'Formaat',
                'choices' => [
                    'Klein (25 cm)' => 'small',
                    'Middel (30 cm)' => 'medium',
                    'Groot (35 cm)' => 'large',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Pizza opslaan',
                'attr' => ['class' => 'btn btn-warning btn-lg w-100'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pizza::class,
        ]);
    }
}
