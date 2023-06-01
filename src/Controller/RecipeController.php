<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Recipe::class);
        $recipes = $repository->findAll();
        $repository = $doctrine->getRepository(Diet::class);
        $diets = $repository->findAll();
        $repository = $doctrine->getRepository(Allergen::class);
        $allergens = $repository->findAll();
        return $this->render('recipe/recipe.html.twig', [
            'recipes' => $recipes,
            'diets' => $diets,
            'allergens' => $allergens
        ]);
    }



    #[Route('/recipe/upload', name: 'addrecipe')]
public function recipe(Request $request, ManagerRegistry $doctrine, ManagerRegistry $managerRegistry, SluggerInterface $slugger): Response
{
    if ($this->isGranted('ROLE_ADMIN')) {
        $recipes = new Recipe();
        $recipesForm = $this->createForm(RecipeType::class, $recipes);
        $recipesForm->handleRequest($request);

        if ($recipesForm->isSubmitted() && $recipesForm->isValid()) {
            
            $image = $recipesForm->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension(); // le nom du fichier est rendu unique grace au uniqid
                try {
                    $image->move(
                        $this->getParameter('uploads'),
                        // deplace l'image dans un dossier uploads
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e);
                }
                $recipes->setImage($newFilename);
            }

            $allergenIds = $recipesForm->get('allergy')->getData();
           
            $dietIds = $recipesForm->get('type')->getData();
            
            $entityManager = $managerRegistry->getManager();

                        $allergens = [];
            foreach ($allergenIds as $allergenId) {
                $allergen = new Allergen();
                $allergen->setAllergy([$allergenId]); // Convertir la chaîne en tableau
                $entityManager->persist($allergen);
                $allergens[] = $allergen;
            }

            $diets = [];
            foreach ($dietIds as $dietId) {
                $diet = new Diet();
                $diet->setType([$dietId]); // Convertir la chaîne en tableau
                $entityManager->persist($diet);
                $diets[] = $diet;
            }

            // Associer les objets Allergen à la recette
            foreach ($allergens as $allergen) {
                $recipes->addAllergen($allergen);
            }

            // Associer les objets Diet à la recette
            foreach ($diets as $diet) {
                $recipes->addDiet($diet);
            }

                    


            $em = $doctrine->getManager();
            $em->persist($recipes);
            $em->flush();

            return $this->redirectToRoute("recipe");
        }

        return $this->render('recipe/formrecipe.html.twig', [
            "recipes" => $recipesForm->createView()
        ]);
    }

    return $this->redirectToRoute("home");
}

}
