<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/** Registratie patiënt (opdracht stap 4). */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Voornaam'])
            ->add('lastName', TextType::class, ['label' => 'Achternaam'])
            ->add('address', TextType::class, ['label' => 'Adres'])
            ->add('zipCode', TextType::class, ['label' => 'Postcode'])
            ->add('city', TextType::class, ['label' => 'Plaats'])
            ->add('email', EmailType::class, ['label' => 'E-mailadres'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'Wachtwoord'],
                'second_options' => ['label' => 'Herhaal wachtwoord'],
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 6),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Ik ga akkoord met de voorwaarden',
                'mapped' => false,
                'constraints' => [new IsTrue()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
