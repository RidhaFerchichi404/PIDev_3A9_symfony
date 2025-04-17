<?php

// src/Form/AbonnementType.php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Salledesport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\NotBlank;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                ],
                'attr' => ['maxlength' => 255]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 5, 'maxlength' => 1000]
            ])
            ->add('duree', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La durée est obligatoire']),
                    new Positive(['message' => 'La durée doit être positive']),
                ],
                'html5' => true,
                'attr' => ['min' => 1]
            ])
            ->add('prix', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est obligatoire']),
                    new Positive(['message' => 'Le prix doit être positif']),
                ],
                'scale' => 2,
                'html5' => true,
                'attr' => ['step' => '0.01', 'min' => 0]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Mensuel' => 'Mensuel',
                    'Trimestriel' => 'Trimestriel',
                    'Annuel' => 'Annuel',
                    'Hebdomadaire' => 'Hebdomadaire',
                ],
                'placeholder' => 'Choisir un type',
                'constraints' => [
                    new NotBlank(['message' => 'Le type est obligatoire']),
                ],
            ])
            ->add('salle', EntityType::class, [
                'class' => Salledesport::class,
                'choice_label' => 'nom',
                'constraints' => [
                    new NotBlank(['message' => 'La salle est obligatoire']),
                ],
                'placeholder' => 'Sélectionner une salle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
            'validation_groups' => ['Default', 'creation'],
        ]);
    }
}
