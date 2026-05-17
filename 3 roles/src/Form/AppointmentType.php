<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/** Afspraak CRUD specialist (stap 7) – alleen patiëntnamen in dropdown (stap 6). */
class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'label' => 'Patiënt',
                'class' => Patient::class,
                'choice_label' => fn (Patient $patient) => $patient->getFullName(),
                'query_builder' => fn (PatientRepository $repo) => $repo->createQueryBuilder('p')
                    ->orderBy('p.lastName', 'ASC'),
                'placeholder' => 'Kies een patiënt',
            ])
            ->add('scheduledAt', DateTimeType::class, [
                'label' => 'Datum en tijd',
                'widget' => 'single_text',
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Opmerkingen',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
