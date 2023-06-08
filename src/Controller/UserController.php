<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

  
    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute("home");
        }

        $user = new User($userPasswordHasher);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergenIds = $form->get('allergy')->getData();
            $dietIds = $form->get('type')->getData();

            // Récupérer tous les utilisateurs
            $recipes = $recipeRepository->findAll();

            // Filtrer les utilisateurs ayant des allergies et des types de régimes différents de ceux de l'utilisateur
            $filteredRecipes = [];
            foreach ($recipes as $existingRecipe) {
                $recipeAllergens = $existingRecipe->getAllergens();
                $recipeDiets = $existingRecipe->getDiets();

                $hasDifferentAllergens = true;

                foreach ($allergenIds as $allergenId) {
                    $found = false;
                    foreach ($recipeAllergens as $recipeAllergen) {
                        if ($allergenId == $recipeAllergen->getAllergy()) {
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        $hasDifferentAllergens = false;
                        break;
                    }
                }

                $hasSameDiets = false; // Initialiser à false avant la boucle
                foreach ($dietIds as $dietId) {
                    $found = false; // Initialiser à false avant chaque itération
                    foreach ($recipeDiets as $recipeDiet) {
                        if ($dietId == $recipeDiet->getType()) {
                            $found = true;
                            break;
                        }
                    }

                    if ($found) { // Utiliser !$found pour vérifier l'absence de correspondance
                        $hasSameDiets = true;
                        break;
                    }
                }

                if ($hasDifferentAllergens && $hasSameDiets) {
                    $filteredRecipes[] = $existingRecipe;
                }
            }

            $user->getId();

            if (!empty($filteredRecipes)) {
                // Associer tous les utilisateurs disponibles à l'utilisateur
                foreach ($filteredRecipes as $existingRecipe) {
                    $user->addRecipe($existingRecipe);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();
            

            $entityManager = $managerRegistry->getManager();

            $allergens = [];
            $allergenRepository = $entityManager->getRepository(Allergen::class);
            foreach ($allergenIds as $allergenId) {
                $existingAllergen = $allergenRepository->findOneBy(['allergy' => $allergenId]);
                if ($existingAllergen) {
                    // L'allergie existe déjà en base de données
                    // Utilise $existingAllergen au lieu de créer une nouvelle instance d'Allergen
                    $allergen = $existingAllergen;
                } else {
                    // L'allergie n'existe pas encore en base de données, créez une nouvelle instance d'Allergen
                    $allergen = new Allergen();
                    $allergen->setAllergy($allergenId); // Convertir la chaîne en tableau
                    $entityManager->persist($allergen);
                }

                $allergens[] = $allergen;
            }

            $diets = [];
            $dietRepository = $entityManager->getRepository(Diet::class);
            foreach ($dietIds as $dietId) {
                $existingDiet = $dietRepository->findOneBy(['type' => $dietId]);
                if ($existingDiet) {
                    // Le type de régime existe déjà en base de données
                    // Utilise $existingDiet au lieu de créer une nouvelle instance de Diet
                    $diet = $existingDiet;
                } else {
                    // Le type de régime n'existe pas encore en base de données, créez une nouvelle instance de Diet
                    $diet = new Diet();
                    $diet->setType($dietId); // Convertir la chaîne en tableau
                    $entityManager->persist($diet);
                }

                $diets[] = $diet;
            }

            // Associe les objets Allergen à l'utilisateur
            foreach ($allergens as $allergen) {
                $user->addAllergen($allergen);
            }

            // Associe les objets Diet à l'utilisateur
            foreach ($diets as $diet) {
                $user->addDiet($diet);
            }


            $entityManager->flush();
        }

        return $this->render('user/form.html.twig', [
            "form" => $form->createView(),
        ]);
    }

}