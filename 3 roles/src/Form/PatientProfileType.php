<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** Profiel patiënt bekijken/aanpassen. */
class PatientProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Voornaam'])
            ->add('lastName', TextType::class, ['label' => 'Achternaam'])
            ->add('address', TextType::class, ['label' => 'Adres'])
            ->add('zipCode', TextType::class, ['label' => 'Postcode'])
            ->add('city', TextType::class, ['label' => 'Plaats'])
            ->add('email', EmailType::class, ['label' => 'E-mailadres']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
