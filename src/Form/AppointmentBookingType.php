<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Treatment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

// Formulier om online een afspraak te reserveren (incl. akkoord AV).
class AppointmentBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $slotHelp = $options['slots_requested'] && $options['slot_choices'] === []
            ? 'Alle kappers zijn bezet op deze dag. Kies een andere datum.'
            : null;

        $builder
            ->add('customerName', TextType::class, ['label' => 'Naam', 'constraints' => [new Assert\NotBlank()]])
            ->add('customerEmail', EmailType::class, ['label' => 'E-mail', 'constraints' => [new Assert\NotBlank(), new Assert\Email()]])
            ->add('customerPhone', TelType::class, ['label' => 'Telefoon', 'constraints' => [new Assert\NotBlank()]])
            ->add('treatment', EntityType::class, [
                'class' => Treatment::class,
                'choice_label' => 'name',
                'label' => 'Behandeling',
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'label' => 'Kapster',
                'placeholder' => 'Kies kapster',
            ])
            ->add('date', DateType::class, ['widget' => 'single_text', 'label' => 'Datum'])
            ->add('slot', ChoiceType::class, [
                'label' => 'Tijd',
                'choices' => $options['slot_choices'],
                'placeholder' => count($options['slot_choices']) ? 'Kies tijd' : 'Geen tijden beschikbaar',
                'disabled' => $options['slot_choices'] === [],
                'help' => $slotHelp,
            ])
            ->add('acceptTerms', CheckboxType::class, [
                'label' => 'Ik ga akkoord met de algemene voorwaarden',
                'mapped' => false,
                'constraints' => [new Assert\IsTrue(message: 'U moet akkoord gaan met de algemene voorwaarden.')],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Reserveren', 'attr' => ['class' => 'btn btn-olive']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'slot_choices' => [],
            'slots_requested' => false,
        ]);
    }
}
