<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your first name'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your first name',
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your last name'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your last name',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your phone number'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9+\s]{8,15}$/',
                        'message' => 'Please enter a valid phone number',
                    ]),
                ],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your age'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your age',
                    ]),
                    new Range([
                        'min' => 16,
                        'max' => 120,
                        'notInRangeMessage' => 'Age must be between {{ min }} and {{ max }} years old',
                    ]),
                ],
            ])
            ->add('cin', TextType::class, [
                'label' => 'CIN (ID Card)',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your ID card number'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your ID card number',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 12,
                        'minMessage' => 'Your ID card number should have at least {{ limit }} characters',
                        'maxMessage' => 'Your ID card number should not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Address',
                'required' => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your address'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your address',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Your address should have at least {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to the terms and conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Enter your password'],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Confirm your password'],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 