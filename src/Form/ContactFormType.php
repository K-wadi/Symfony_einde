<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Naam', 'constraints' => [new Assert\NotBlank()]])
            ->add('email', EmailType::class, ['label' => 'E-mail', 'constraints' => [new Assert\NotBlank(), new Assert\Email()]])
            ->add('message', TextareaType::class, ['label' => 'Bericht', 'constraints' => [new Assert\NotBlank()]])
            ->add('submit', SubmitType::class, ['label' => 'Versturen', 'attr' => ['class' => 'btn btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_token_id' => 'contact']);
    }
}
