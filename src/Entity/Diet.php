<?php

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietRepository::class)]
class Diet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?array $type = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of type
     */
    public function getType(): ?array
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType(?array $type): self
    {
        $this->type = $type;

        return $this;
    }
}
