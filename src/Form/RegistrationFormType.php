<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mailadres',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Voer je e-mailadres in'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Wachtwoord',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Voer je wachtwoord in'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Voer een wachtwoord in',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Je wachtwoord moet minimaal {{ limit }} karakters lang zijn',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Herhaal wachtwoord',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Herhaal je wachtwoord'
                    ]
                ],
                'invalid_message' => 'De wachtwoorden komen niet overeen.',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Ik ga akkoord met de voorwaarden',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Je moet akkoord gaan met de voorwaarden.',
                    ]),
                ],
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Registreren',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 