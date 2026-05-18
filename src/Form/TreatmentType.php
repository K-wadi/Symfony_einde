<?php

namespace App\Form;

use App\Entity\Treatment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreatmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Naam'])
            ->add('description', TextareaType::class, ['label' => 'Beschrijving', 'required' => false])
            ->add('durationMinutes', IntegerType::class, ['label' => 'Duur (minuten)'])
            ->add('priceEuro', MoneyType::class, [
                'label' => 'Prijs',
                'currency' => 'EUR',
                'divisor' => 100,
                'mapped' => false,
                'data' => $options['data']?->getPriceCents() ? $options['data']->getPriceCents() / 100 : null,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Treatment::class]);
    }
}
