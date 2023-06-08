<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeController extends AbstractController
{
   

    #[Route('/recipe', name: 'recipe')]
    public function index (ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Diet::class);
        $diets = $repository->findAll();
        $repository = $doctrine->getRepository(Allergen::class);
        $allergens = $repository->findAll();
        $repository = $doctrine->getRepository(Recipe::class);
        $allRecipes = $repository->findAll();

        $accessibleRecipes = [];

        foreach ($allRecipes as $recipe) {
            if (!$recipe->getIsOnlyAccessibleToPatients()) {
                $accessibleRecipes[] = $recipe;
            }
        }

        $user = $this->getUser();

        if ($user instanceof User) {
            $recipes = $user->getRecipes();
          
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $recipes = $allRecipes;
            
        } else {
            $recipes = $accessibleRecipes;
            
        }
        

        return $this->render('recipe/recipe.html.twig', [
            'recipes' => $recipes,
            'diets' => $diets,
            'allergens' => $allergens
        ]);
    }


       #[Route('/recipe/upload', name: 'addrecipe')]
       public function addRecipe(Request $request, ManagerRegistry $doctrine, ManagerRegistry $managerRegistry, UserRepository $userRepository, SluggerInterface $slugger): Response
       {
           if ($this->isGranted('ROLE_ADMIN')) {
              
               $recipe = new Recipe();
               $recipeForm = $this->createForm(RecipeType::class, $recipe);
               $recipeForm->handleRequest($request);
       
               if ($recipeForm->isSubmitted() && $recipeForm->isValid()) {
                $isOnlyAccessibleToPatients = $recipeForm->get('isOnlyAccessibleToPatients')->getData();
                $allergenIds = $recipeForm->get('allergy')->getData();
                $dietIds = $recipeForm->get('type')->getData();
                

                if ($isOnlyAccessibleToPatients) {
                    // Associe la recette à tous les patients avec le role user
                    $users = $userRepository->findBy(['roles' => 'ROLE_USER']);
        
                    foreach ($users as $user) {
                        $recipe->addUser($user);
                    }
                }
                // Récupére tous les utilisateurs
                $users = $userRepository->findAll();
            
                // Filtre les utilisateurs ayant des allergies et des types de régimes différents de ceux de la recette
                $filteredUsers = [];
                foreach ($users as $user) {
                    $userAllergens = $user->getAllergens();
                    $userDiets = $user->getDiets();

                    $hasDifferentAllergens = true;

                    foreach ($allergenIds as $allergenId) {
                        $found = false;
                        foreach ($userAllergens as $userAllergen) {
                            if ($allergenId == $userAllergen->getAllergy()) {
                                $found = true;
                                break;
                            }
                        }
                        
                        if ($found) {
                            $hasDifferentAllergens = false;
                            break;
                        }
                    }
                    

                    $hasSameDiets = false; // Initialise à false avant la boucle
                    foreach ($dietIds as $dietId) {
                        $found = false; // Initialise à false avant chaque itération
                        foreach ($userDiets as $userDiet) {
                            if ($dietId == $userDiet->getType()) {
                                $found = true;
                                break;
                            }
                        }
                       
                        if ($found) { // Utilise !$found pour vérifier l'absence de correspondance
                            $hasSameDiets = true;
                            break;
                        }
                    }
                    

                    if ($hasDifferentAllergens && $hasSameDiets) {
                        $filteredUsers[] = $user;
                    }
                }
            
                if (!empty($filteredUsers)) {
                    // Associe tous les utilisateurs disponibles à la recette
                    foreach ($filteredUsers as $user) {
                        $recipe->addUser($user);
                    }
                }
               
           
                   
                   $image = $recipeForm->get('image')->getData();
                   if ($image) {
                       $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                       $safeFilename = $slugger->slug($originalFilename);
                       $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension(); // le nom du fichier est rendu unique grâce au uniqid
                       try {
                           $image->move(
                               $this->getParameter('uploads'),
                               // déplace l'image dans un dossier uploads
                               $newFilename
                           );
                       } catch (FileException $e) {
                           dump($e);
                       }
                       $recipe->setImage($newFilename);
                   }
       
    
                   $allergenIds = $recipeForm->get('allergy')->getData();
                   $dietIds = $recipeForm->get('type')->getData();
               
                   $entityManager = $managerRegistry->getManager();

                   $allergens = [];
                   foreach ($allergenIds as $allergenId) {
                       $existingAllergen = $entityManager->getRepository(Allergen::class)->findOneBy(['allergy' => $allergenId]);
                       if (!$existingAllergen) {
                           $allergen = new Allergen();
                           $allergen->setAllergy($allergenId);
                           $entityManager->persist($allergen);
                           $allergens[] = $allergen;
                       } else {
                           $allergens[] = $existingAllergen;
                       }
                   }
                   
                   $diets = [];
                   foreach ($dietIds as $dietId) {
                       $existingDiet = $entityManager->getRepository(Diet::class)->findOneBy(['type' => $dietId]);
                       if (!$existingDiet) {
                           $diet = new Diet();
                           $diet->setType($dietId);
                           $entityManager->persist($diet);
                           $diets[] = $diet;
                       } else {
                           $diets[] = $existingDiet;
                       }
                   }
               
                   // Associe les objets Allergen à la recette
                   foreach ($allergens as $allergen) {
                       $recipe->addAllergen($allergen);
                   }
               
                   // Associe les objets Diet à la recette
                   foreach ($diets as $diet) {
                       $recipe->addDiet($diet);
                   }
       
                   $em = $doctrine->getManager();
                   $em->persist($recipe);
                   $em->flush();
       
                  
               }
       
               return $this->render('recipe/formrecipe.html.twig', [
                   "recipes" => $recipeForm->createView()
               ]);
           }
       
           return $this->redirectToRoute("home");
       }





       #[Route('/recipe/delete/{id<\d+>}', name: 'deleterecipe')]
       public function deleteRecipe(Recipe $recipe, ManagerRegistry $doctrine): Response
       {
           if ($this->isGranted('ROLE_ADMIN')) {
               $em = $doctrine->getManager();
               $em->remove($recipe);
               $em->flush();
           }
       
           return $this->redirectToRoute("home");
       }


       #[Route('/recipe/edit/{id<\d+>}', name: 'editrecipe')]
        public function updateRecipe(Request $request, ManagerRegistry $doctrine, UserRepository $userRepository, SluggerInterface $slugger): Response
        {
            if ($this->isGranted('ROLE_ADMIN')) {
              
                $recipe = new Recipe();
                $recipeForm = $this->createForm(RecipeType::class, $recipe);
                $recipeForm->handleRequest($request);
        
                if ($recipeForm->isSubmitted() && $recipeForm->isValid()) {
                 $isOnlyAccessibleToPatients = $recipeForm->get('isOnlyAccessibleToPatients')->getData();
                 $allergenIds = $recipeForm->get('allergy')->getData();
                 $dietIds = $recipeForm->get('type')->getData();
                 
 
                 if ($isOnlyAccessibleToPatients) {
                     // Associe la recette à tous les patients avec le role user
                     $users = $userRepository->findBy(['roles' => 'ROLE_USER']);
         
                     foreach ($users as $user) {
                         $recipe->addUser($user);
                     }
                 }
                 // Récupére tous les utilisateurs
                 $users = $userRepository->findAll();
             
                 // Filtre les utilisateurs ayant des allergies et des types de régimes différents de ceux de la recette
                 $filteredUsers = [];
                 foreach ($users as $user) {
                     $userAllergens = $user->getAllergens();
                     $userDiets = $user->getDiets();
 
                     $hasDifferentAllergens = true;
 
                     foreach ($allergenIds as $allergenId) {
                         $found = false;
                         foreach ($userAllergens as $userAllergen) {
                             if ($allergenId == $userAllergen->getAllergy()) {
                                 $found = true;
                                 break;
                             }
                         }
                         
                         if ($found) {
                             $hasDifferentAllergens = false;
                             break;
                         }
                     }
                     
 
                     $hasSameDiets = false; // Initialise à false avant la boucle
                     foreach ($dietIds as $dietId) {
                         $found = false; // Initialise à false avant chaque itération
                         foreach ($userDiets as $userDiet) {
                             if ($dietId == $userDiet->getType()) {
                                 $found = true;
                                 break;
                             }
                         }
                        
                         if ($found) { // Utilise !$found pour vérifier l'absence de correspondance
                             $hasSameDiets = true;
                             break;
                         }
                     }
                     
 
                     if ($hasDifferentAllergens && $hasSameDiets) {
                         $filteredUsers[] = $user;
                     }
                 }
             
                 if (!empty($filteredUsers)) {
                     // Associe tous les utilisateurs disponibles à la recette
                     foreach ($filteredUsers as $user) {
                         $recipe->addUser($user);
                     }
                 }
                
            
                    
                    $image = $recipeForm->get('image')->getData();
                    if ($image) {
                        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension(); // le nom du fichier est rendu unique grâce au uniqid
                        try {
                            $image->move(
                                $this->getParameter('uploads'),
                                // déplace l'image dans un dossier uploads
                                $newFilename
                            );
                        } catch (FileException $e) {
                            dump($e);
                        }
                        $recipe->setImage($newFilename);
                    }
        
     
                    $allergenIds = $recipeForm->get('allergy')->getData();
                    $dietIds = $recipeForm->get('type')->getData();
                
                    $entityManager = $doctrine->getManager();
 
                    $allergens = [];
                    foreach ($allergenIds as $allergenId) {
                        $existingAllergen = $entityManager->getRepository(Allergen::class)->findOneBy(['allergy' => $allergenId]);
                        if (!$existingAllergen) {
                            $allergen = new Allergen();
                            $allergen->setAllergy($allergenId);
                            $entityManager->persist($allergen);
                            $allergens[] = $allergen;
                        } else {
                            $allergens[] = $existingAllergen;
                        }
                    }
                    
                    $diets = [];
                    foreach ($dietIds as $dietId) {
                        $existingDiet = $entityManager->getRepository(Diet::class)->findOneBy(['type' => $dietId]);
                        if (!$existingDiet) {
                            $diet = new Diet();
                            $diet->setType($dietId);
                            $entityManager->persist($diet);
                            $diets[] = $diet;
                        } else {
                            $diets[] = $existingDiet;
                        }
                    }
                
                    // Associe les objets Allergen à la recette
                    foreach ($allergens as $allergen) {
                        $recipe->addAllergen($allergen);
                    }
                
                    // Associe les objets Diet à la recette
                    foreach ($diets as $diet) {
                        $recipe->addDiet($diet);
                    }
        
                    $em = $doctrine->getManager();
                    $em->persist($recipe);
                    $em->flush();
        
                   
                }
        
                return $this->render('recipe/formrecipe.html.twig', [
                    "recipes" => $recipeForm->createView()
                ]);
            }
        
            return $this->redirectToRoute("home");
        }
 

        #[Route('/recipe/rate', name: 'rate_recipe', methods: ['POST'])]
        public function rateRecipe(Request $request, EntityManagerInterface $entityManager): JsonResponse
        {
            // Vérification du rôle de l'utilisateur avant de traiter la requête Ajax
            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_USER')) {
                $content = $request->getContent();
                $data = json_decode($content, true);
            
                $recipeId = $data['recipeId'];
                $rating = $data['rating'];
            
                // Récupére la recette à partir de l'ID
                $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);
            
                if ($recipe) {
                    // Vérifie si l'utilisateur a déjà noté la recette
                    $user = $this->getUser();
                    if ($user && $recipe->getUsers()->contains($user)) {
                        throw new HttpException(400, 'Vous avez déjà noté cette recette');
                    }
                    
            
                    // Récupére la note moyenne existante et le nombre total de notes
                    $averageRating = $recipe->getNote();
                    $totalRatings = $recipe->getTotalRatings();
        
                    // Mettre à jour la note moyenne et le nombre total de notes
                    $newTotalRatings = $totalRatings + 1;
                    $newAverageRating = (($averageRating * $totalRatings) + $rating) / $newTotalRatings;
        
                    $recipe->setNote($newAverageRating);
                    $recipe->setTotalRatings($newTotalRatings);
            
                    // Ajoute l'utilisateur à la liste des utilisateurs ayant noté la recette
                    $recipe->addUser($user);
            
                    $entityManager->persist($recipe);
                    $entityManager->flush();
            
                    // Retourne les données mises à jour
                    $responseData = [
                        'averageRating' => $newAverageRating,
                        'totalRatings' => $newTotalRatings,
                    ];
            
                    return new JsonResponse($responseData);
                }
            
                throw new \InvalidArgumentException('Recette introuvable');
            } else {
                // Renvoie une erreur ou une réponse indiquant que l'accès est interdit
                return new JsonResponse(['error' => 'Accès interdit'], 403);
            }
        }
    }        