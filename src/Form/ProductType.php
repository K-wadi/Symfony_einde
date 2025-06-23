<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('naam', TextType::class, [
                'label' => 'Productnaam',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Voer de productnaam in'
                ]
            ])
            ->add('prijs', MoneyType::class, [
                'label' => 'Prijs',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0.00'
                ]
            ])
            ->add('beschrijving', TextareaType::class, [
                'label' => 'Beschrijving',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Voer een beschrijving in (optioneel)'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Opslaan',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
} 