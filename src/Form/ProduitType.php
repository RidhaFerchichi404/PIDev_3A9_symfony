<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 3,
                    'maxlength' => 100,
                    'pattern' => '[A-Za-zÀ-ÿ0-9\s\-]+',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le nom du produit est requis'),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9\s\-]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres, chiffres, espaces et tirets',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'maxlength' => 1000,
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'choices' => [
                    'Équipement' => 'Équipement',
                    'Suppléments' => 'Suppléments',
                    'Nutrition' => 'Nutrition',
                    'Vêtements' => 'Vêtements',
                    'Accessoires' => 'Accessoires',
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['Équipement', 'Suppléments', 'Nutrition', 'Vêtements', 'Accessoires'],
                        'message' => 'Veuillez sélectionner une catégorie valide',
                    ]),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'TND',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0.01,
                    'step' => 0.01,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le prix est requis'),
                    new Assert\Positive(message: 'Le prix doit être positif'),
                    new Assert\LessThanOrEqual(10000, message: 'Le prix ne peut pas dépasser 10000 TND'),
                ],
            ])
            ->add('quantiteStock', NumberType::class, [
                'label' => 'Quantité en stock',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 1000,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'La quantité en stock est requise'),
                    new Assert\PositiveOrZero(message: 'La quantité en stock ne peut pas être négative'),
                    new Assert\LessThanOrEqual(1000, message: 'La quantité en stock ne peut pas dépasser 1000'),
                ],
            ])
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('imagePath', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou GIF)',
                        'maxSizeMessage' => 'L\'image ne peut pas dépasser 2MB',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
} 