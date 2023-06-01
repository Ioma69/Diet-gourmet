<?php

namespace App\Entity;

use App\Repository\AllergenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "allergens")]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'allergens')]
    private Collection $recipes;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }


    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addAllergen($this);
        }
    
        return $this;
    }
    
    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            $recipe->removeAllergen($this);
        }
    
        return $this;
    }



    public function addUser(User $user): self
{
    if (!$this->users->contains($user)) {
        $this->users[] = $user;
        $user->addAllergen($this);
    }

    return $this;
}

public function removeUser(User $user): self
{
    if ($this->users->contains($user)) {
        $this->users->removeElement($user);
        $user->removeAllergen($this);
    }

    return $this;
}


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

    /**
     * Get the value of recipes
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    /**
     * Set the value of recipes
     */
    public function setRecipes(Collection $recipes): self
    {
        $this->recipes = $recipes;

        return $this;
    }
}