<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulier om gebruikers aan te maken via /users/add (opdracht 2.3).
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Voornaam'])
            ->add('lastName', TextType::class, ['label' => 'Achternaam'])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Geboortedatum',
                'widget' => 'single_text',
            ])
            ->add('email', EmailType::class, ['label' => 'E-mailadres'])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Wachtwoord',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Wachtwoord is verplicht.'),
                    new Length(min: 6, minMessage: 'Minimaal {{ limit }} tekens.'),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rollen',
                'choices' => [
                    'Gebruiker (ROLE_USER)' => 'ROLE_USER',
                    'Beheerder (ROLE_ADMIN)' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
