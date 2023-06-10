<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form ->isValid()) {
            $this->addFlash('notice', "Merci de m'avoir contacter. Je vous répondrais dans les meilleurs délais.");

             
            $content = "Bonjour </br>Vous avez reçus un message de <strong>".$form->getData()['prenom']." ".$form->getData()['nom']."</strong></br>Adresse email : <strong>".$form->getData()['email']."</strong> </br>Message : ".$form->getData()['content']."</br></br>"; 

            $email = (new Email())
                -> from ('test@test.fr')
                -> to('contact@sandrine-coupart.fr')
                -> subject ('Vous avez reçu une demande de contact')
                -> text ($content);
                
            $mailer->send($email);
           
            $this->addFlash('success', 'Votre message à bien été envoyé.');
           
        }

        return $this->render('contact/index.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}
