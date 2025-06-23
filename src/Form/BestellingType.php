<?php

namespace App\Form;

use App\Entity\Bestelling;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BestellingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('klantnaam', TextType::class, [
                'label' => 'Klantnaam',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Voer je naam in'
                ]
            ])
            ->add('producten', EntityType::class, [
                'class' => Product::class,
                'choice_label' => function(Product $product) {
                    return $product->getNaam() . ' - €' . number_format((float)$product->getPrijs(), 2, ',', '.');
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Selecteer producten',
                'attr' => [
                    'class' => 'product-checkboxes'
                ],
                'choice_attr' => function() {
                    return ['class' => 'form-check-input'];
                },
                'label_attr' => ['class' => 'form-check-label']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Bestelling plaatsen',
                'attr' => ['class' => 'btn btn-success btn-lg']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bestelling::class,
        ]);
    }
} 