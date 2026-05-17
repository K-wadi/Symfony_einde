<?php

namespace App\Form;

use App\Entity\Smartphone;
use App\Entity\Vendor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formulier voor Smartphone - kleuren hier geconfigureerd (opdracht PDF).
 */
class SmartphoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type / model',
            ])
            ->add('memory', IntegerType::class, [
                'label' => 'Geheugen (GB)',
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Kleur',
                'choices' => [
                    'Zwart' => 'Zwart',
                    'Wit' => 'Wit',
                    'Blauw' => 'Blauw',
                    'Rood' => 'Rood',
                    'Groen' => 'Groen',
                    'Goud' => 'Goud',
                ],
                'placeholder' => 'Kies een kleur',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prijs',
                'currency' => 'EUR',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Beschrijving',
                'required' => false,
            ])
            ->add('vendor', EntityType::class, [
                'label' => 'Vendor',
                'class' => Vendor::class,
                'choice_label' => 'name',
                'placeholder' => 'Kies een vendor',
            ])
            // Unmapped file field - symfony.com/doc/current/controller/upload_file.html
            ->add('pictureFile', FileType::class, [
                'label' => 'Afbeelding',
                'mapped' => false,
                'required' => $options['require_picture'],
                'constraints' => [
                    new Assert\File(
                        maxSize: '2M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Upload een geldige afbeelding (jpg, png of webp).',
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Smartphone::class,
            // Bij create is afbeelding verplicht; bij edit optioneel (opdracht upload)
            'require_picture' => true,
        ]);

        $resolver->setAllowedTypes('require_picture', 'bool');
    }
}
