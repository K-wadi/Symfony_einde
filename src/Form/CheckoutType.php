<?php

namespace App\Form;

use App\Entity\ShopOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

// Afrekenen webwinkel; klant moet akkoord gaan met algemene voorwaarden.
class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, ['label' => 'Naam'])
            ->add('customerEmail', EmailType::class, ['label' => 'E-mail'])
            ->add('deliveryMethod', ChoiceType::class, [
                'label' => 'Aflevering',
                'choices' => [
                    'Ophalen en betalen in de winkel' => ShopOrder::DELIVERY_PICKUP,
                    'Thuisbezorgen' => ShopOrder::DELIVERY_HOME,
                ],
            ])
            ->add('acceptTerms', CheckboxType::class, [
                'label' => 'Ik ga akkoord met de algemene voorwaarden',
                'mapped' => false,
                'constraints' => [new Assert\IsTrue(message: 'U moet akkoord gaan met de algemene voorwaarden.')],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Bestelling plaatsen', 'attr' => ['class' => 'btn btn-olive']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ShopOrder::class]);
    }
}
