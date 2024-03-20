<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
        
    }

    public function load(ObjectManager $manager): void
    {
         $Sandrine = new Admin($this->passwordHasher);
         $Sandrine->setEmail("SandrineCoupart@hotmail.com")->setPassword("SandrineCoupart007*")->setRoles(["ROLE_ADMIN"]);
         $manager->persist($Sandrine);
        
       
    $manager->flush();

    }

}
