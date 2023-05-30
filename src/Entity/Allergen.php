<?php

namespace App\Entity;

use App\Repository\AllergenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllergenRepository::class)]
class Allergen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?array $allergy = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of allergy
     */
    public function getAllergy(): ?array
    {
        return $this->allergy;
    }

    /**
     * Set the value of allergy
     */
    public function setAllergy(?array $allergy): self
    {
        $this->allergy = $allergy;

        return $this;
    }
}
