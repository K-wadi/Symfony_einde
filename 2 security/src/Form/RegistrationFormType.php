<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Registratieformulier – Nederlandse labels (opdracht 2.1).
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Voornaam',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Achternaam',
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Geboortedatum',
                'widget' => 'single_text',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mailadres',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Wachtwoord',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'second_options' => [
                    'label' => 'Herhaal wachtwoord',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'invalid_message' => 'De wachtwoorden komen niet overeen.',
                'constraints' => [
                    new NotBlank(message: 'Kies een wachtwoord.'),
                    new Length(min: 6, minMessage: 'Wachtwoord moet minimaal {{ limit }} tekens zijn.'),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Ik ga akkoord met de voorwaarden',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(message: 'Je moet akkoord gaan met de voorwaarden.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
