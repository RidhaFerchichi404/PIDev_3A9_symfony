<?php

namespace App\Form;

use App\Entity\Equipement;
use App\Entity\SalleDeSport;
 // <-- Ajout important
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('fonctionnement')
            ->add('prochaine_verification', null, [
                'widget' => 'single_text',
            ])
            ->add('derniere_verification', null, [
                'widget' => 'single_text',
            ])
            ->add('salle', EntityType::class, [

                'class' => SalleDeSport::class,
                'choice_label' => 'id',
            ])
             ->add('id_user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipement::class,
        ]);
    }
}
