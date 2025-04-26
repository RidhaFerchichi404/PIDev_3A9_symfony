<?php

namespace App\Form;

use App\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Abonnement;
use Symfony\Component\Validator\Constraints as Assert;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codePromo', TextType::class, [
                'label' => 'Code Promo',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code promo est obligatoire.']),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'Le code promo doit contenir au moins {{ limit }} caractères.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Ex: ETE2023',
                    'minlength' => 5
                ]
            ])
            ->add('valeurReduction', NumberType::class, [
                'label' => 'Pourcentage de réduction',
                'html5' => true,
                'attr' => [
                    'min' => 0.1,
                    'max' => 100,
                    'step' => 0.1,
                    'placeholder' => 'Ex: 20.5 pour 20,5%'
                ],
                'empty_data' => '0.0' // Valeur par défaut si null
            ])
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de début est obligatoire.']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de début ne peut pas être dans le passé.'
                    ])
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i')
                ]
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de fin est obligatoire.']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de fin ne peut pas être dans le passé.'
                    ]),
                    new Assert\Expression([
                        'expression' => 'value >= this.getParent().get("dateDebut").getData()',
                        'message' => 'La date de fin doit être après la date de début.'
                    ])
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i')
                ]
            ])
            ->add('abonnement', EntityType::class, [
                'label' => 'Abonnement associé',
                'class' => Abonnement::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un abonnement',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner un abonnement.'])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['placeholder' => 'Description de la promotion']
            ])
            ->add('typeReduction', ChoiceType::class, [
                'label' => 'Type de réduction',
                'choices' => [
                    'Pourcentage' => 'pourcentage',
                    'Montant fixe' => 'montant_fixe',
                ],
                'placeholder' => 'Optionnel',
                'required' => false // Permet la valeur null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}