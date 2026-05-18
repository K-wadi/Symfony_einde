<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\TimeBlock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employee', EntityType::class, ['class' => Employee::class, 'choice_label' => 'name', 'label' => 'Medewerker'])
            ->add('startAt', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Start'])
            ->add('endAt', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Einde'])
            ->add('submit', SubmitType::class, ['label' => 'Opslaan']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TimeBlock::class]);
    }
}
