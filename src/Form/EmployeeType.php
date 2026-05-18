<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Naam'])
            ->add('specialization', TextType::class, ['label' => 'Specialisatie', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Login e-mail', 'mapped' => false, 'required' => $options['require_login']])
            ->add('password', PasswordType::class, ['label' => 'Wachtwoord', 'mapped' => false, 'required' => $options['require_login']])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'require_login' => true,
        ]);
    }
}
