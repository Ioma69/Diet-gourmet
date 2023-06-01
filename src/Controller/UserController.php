<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Diet;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry): Response
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

            // Associer les objets Allergen à l'utilisateur
            foreach ($allergens as $allergen) {
                $user->addAllergen($allergen);
            }

            // Associer les objets Diet à l'utilisateur
            foreach ($diets as $diet) {
                $user->addDiet($diet);
            }

            $entityManager->persist($user);
            $entityManager->flush();

          
        }

        return $this->render('user/form.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
