<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isNew = $options['is_new'] ?? false;
        
        $builder
            ->add('first_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Prénom'
            ])
            ->add('last_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email'
            ])
            ->add('password_hash', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Mot de passe',
                'required' => $isNew,
                'help' => $isNew ? 'Définissez un mot de passe pour ce nouvel utilisateur' : 'Laissez vide pour conserver le mot de passe actuel'
            ])
            ->add('phone_number', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Rôle'
            ])
            ->add('isactive', ChoiceType::class, [
                'choices' => [
                    'Actif' => true,
                    'Inactif' => false
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Statut'
            ])
            ->add('subscriptionenddate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de fin d\'abonnement'
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new' => false
        ]);
    }
}
