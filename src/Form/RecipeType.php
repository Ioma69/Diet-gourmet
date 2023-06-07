<?php

namespace App\Form;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContext;

class RecipeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void /* Création d'un type de formulaire */
    {
        $builder

            ->add("name", TextType::class, [
                "label" => "nom",
                "required" => true,
                "constraints" => [
                    new NotBlank(["message" => "Le nom ne peut pas être vide !"]),
                ]
            ])

            ->add(
                "description", TextType::class,
                [
                    "label" => "Description",
                    "required" => true,
                    'constraints'
                    => [
                        new NotBlank(['message' => "Veuillez renseigner votre nom"]),
                    ]
                ]
            )

            ->add("image", FileType::class, [
                "label" => "L'image",
                'mapped' => false,
                "required" => false,
                'constraints' => [
                    new NotBlank(['message' => "Le contenu ne doit pas etre vide"]),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            "image/gif",
                            "image/png",
                            "image/svg+xml",
                            "image/jpg",
                            "image/webp"
                        ],
                        'mimeTypesMessage' => 'Veuillez proposer une image valide.',
                    ])
                ],
            ])



            ->add(
                "preparation_time", NumberType::class,
                [
                    "label" => "Temps de préparation",
                    "required" => true,
                    "constraints" /*Ajout de contraites de validation grace au composant validator*/
                    => [
                        new NotBlank(['message' => "Le contenu ne doit pas etre vide et/ou doit contenir plus d'un caractere"])
                    ]
                ]
            )

            
            ->add(
                "cooking_time", NumberType::class,
                [
                    "label" => "Temps de cuisson",
                    "required" => true,
                    "constraints" /*Ajout de contraites de validation grace au composant validator*/
                    => [
                        new NotBlank(['message' => "Le contenu ne doit pas etre vide et/ou doit contenir plus d'un caractere"])
                    ]
                ]
            )

            ->add(
                "ingredients", TextType::class,
                [
                    "label" => "ingredients",
                    "required" => true,
                    'constraints'
                    => [
                        new NotBlank(['message' => "Le mot de passe ne doit pas etre vide"]),
                       
                    ]
                ]
            )

            ->add(
                "preparation", TextType::class,
                [
                    "label" => "preparation",
                    "required" => true,
                    'constraints'
                    => [
                        new NotBlank(['message' => "Le mot de passe ne doit pas etre vide"]),
                    ]
                ])
            
                ->add('allergy', ChoiceType::class, [
                    'label' => "Types d'allergènes :",
                    'required' => false,
                    'choices' => [
                        'Arachide' => 'Arachide',
                        'Lactose' => 'Lactose',
                        'Gluten' => 'Gluten',
                        'Fruits rouges' => 'Fruits rouges',
                        'Oeufs' => 'Oeufs',
    
                      
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false
                ])
                
                ->add('type', ChoiceType::class, [
                    'label' => 'Types de régimes :',
                    'required' => false,
                    'choices' => [
                        'Régime végétalien' => 'Régime végétalien',
                        'Régime végétarien' => 'Régime végétarien',
                        'Régime Paléo' => 'Régime Paléo',
                        'Régime sans sel' => 'Régime sans sel',
                        'Régime anticholestérol' => 'Régime anticholestérol',
                        
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false
                ])

                ->add('isOnlyAccessibleToPatients', CheckboxType::class, [
                    'label' => 'Uniquement accessible aux patients',
                    'required' => false,
                ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Recipe::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'recipe_item',
        ]);
    }
}