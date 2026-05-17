<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** Tweede formulier (opdracht forms 1.5). */
class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Voornaam',
                'help' => 'Je officiële voornaam.',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Achternaam',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mailadres',
                'help' => 'We sturen bevestigingen naar dit adres.',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Klant registreren',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
