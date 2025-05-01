<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => function (Produit $produit) {
                    return $produit->getNom() . ' - ' . $produit->getPrix() . ' TND (Stock: ' . $produit->getQuantiteStock() . ')';
                },
                'placeholder' => 'Choisir un produit',
                'attr' => ['class' => 'form-control'],
                'label' => 'Produit',
                'constraints' => [
                    new Assert\NotNull(message: 'Veuillez sélectionner un produit'),
                ],
            ])
            ->add('nomClient', TextType::class, [
                'label' => 'Nom du client',
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 100,
                    'pattern' => '[A-Za-zÀ-ÿ\s\-]+',
                    'placeholder' => 'Entrez votre nom complet',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le nom du client est requis'),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ\s\-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces et tirets',
                    ]),
                ],
            ])
            ->add('adresseLivraison', TextareaType::class, [
                'label' => 'Adresse de livraison',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'minlength' => 10,
                    'maxlength' => 500,
                    'placeholder' => 'Entrez votre adresse complète',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'L\'adresse de livraison est requise'),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 500,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'form-control',
                    'pattern' => '[0-9\-\+\s\(\)]{8,20}',
                    'placeholder' => 'Ex: +216 12 345 678',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le numéro de téléphone est requis'),
                    new Assert\Regex([
                        'pattern' => '/^[0-9\-\+\s\(\)]{8,20}$/',
                        'message' => 'Format de téléphone invalide',
                    ]),
                ],
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 100,
                    'placeholder' => 'Entrez la quantité souhaitée',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'La quantité est requise'),
                    new Assert\Positive(message: 'La quantité doit être positive'),
                    new Assert\LessThanOrEqual(100, message: 'La quantité ne peut pas dépasser 100'),
                ],
            ])
        ;

        // Add stock validation after the form is submitted
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $commande = $event->getData();
            $form = $event->getForm();

            if ($commande && $commande->getProduit()) {
                $produit = $commande->getProduit();
                $requestedQuantity = $commande->getQuantite();
                $currentStock = $produit->getQuantiteStock();

                if ($requestedQuantity > $currentStock) {
                    $form->get('quantite')->addError(new \Symfony\Component\Form\FormError(
                        'Stock insuffisant. Il ne reste que ' . $currentStock . ' unités en stock.'
                    ));
                }
            }
        });
        
        // Add status field only for edit form
        if ($options['is_edit'] ?? false) {
            $builder->add('statutCommande', ChoiceType::class, [
                'label' => 'Statut de la commande',
                'choices' => array_combine(Commande::getAvailableStatuses(), Commande::getAvailableStatuses()),
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotNull(message: 'Le statut de la commande est requis'),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'is_edit' => false,
        ]);
    }
} 