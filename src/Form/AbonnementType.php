<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Salledesport;


use Symfony\Component\Form\Extension\Core\Type\IntegerType; // ✅

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [ // Notez la majuscule si votre propriété s'appelle 'Nom'
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez le nom de l\'abonnement']
            ])
            ->add('Description', TextType::class, [ // Ajout du champ Description
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description de l\'abonnement']
            ])
            ->add('Prix', NumberType::class, [ // Changé en NumberType pour le prix
                'label' => 'Prix (TND)',
                'attr' => [
                    'placeholder' => 'Prix en TND',
                    'step' => '0.01'
                ]
            ])
            ->add('Duree', IntegerType::class, [ // Ajout du champ Durée
                'label' => 'Durée (jours)',
                'attr' => ['placeholder' => 'Durée en jours']
            ])
            ->add('salle', EntityType::class, [
                'class' => SalleDeSport::class,
                'choice_label' => 'nom',
                'label' => 'Salle de sport',
                'placeholder' => 'Choisir une salle',
                'required' => false // Si le champ peut être null
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
