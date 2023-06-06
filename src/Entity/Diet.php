<?php

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DietRepository::class)]

class Diet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $type = null;


    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "diets")]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'diets')]
    private Collection $recipes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addDiet($this);
        }
    
        return $this;
    }
    
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeDiet($this);
        }
    
        return $this;
    }


    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addDiet($this);
        }
    
        return $this;
    }
    
    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->removeDiet($this);
        }
    
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of type
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get the value of users
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Set the value of users
     */
    public function setUsers(Collection $users): self
    {
        $this->users = $users;

        return $this;
    }
}
