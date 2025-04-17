<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\Abonnement;
use App\Entity\Salledesport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codePromo', TextType::class, [
                'label' => 'Code Promotion',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: PROMO2024'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3
                ],
                'required' => true
            ])
            ->add('typeReduction', TextType::class, [
                'label' => 'Type de réduction',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Pourcentage ou Montant fixe'
                ],
                'required' => true
            ])
            ->add('valeurReduction', NumberType::class, [
                'label' => 'Valeur de réduction',
                'attr' => [
                    'class' => 'form-control',
                    'step' => '0.01'
                ],
                'required' => true
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('abonnement', EntityType::class, [
                'label' => 'Abonnement associé',
                'class' => Abonnement::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un abonnement',
                'required' => false
            ])
            ->add('salle', EntityType::class, [
                'label' => 'Salle de sport',
                'class' => Salledesport::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une salle',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'attr' => [
                'class' => 'needs-validation',
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}