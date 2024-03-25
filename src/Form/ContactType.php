<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom',
                    'class' => 'colorText border-black'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom',
                    'class' => 'colorText border-black'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' =>'Votre Email',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email',
                    'class' => 'colorText border-black'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'placeholder' => 'En quoi puis-je vous aidez ?',
                    'class' => 'colorText border-black'
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' =>'btn btn-secondary btnColor colorText mt-2  border-black'
                ]
            ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                "data_class" => null,
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'csrf_token_id' => 'contact_item',
        ]);
    }
}
