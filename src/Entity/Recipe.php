<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: "text")]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $preparation_time = null;

    #[ORM\Column]
    private ?int $cooking_time = null;

    #[ORM\Column(length: 255)]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255)]
    private ?string $preparation = null;

    #[ORM\Column(type:"boolean")]
    private ?bool $isOnlyAccessibleToPatients;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $note = null;

    #[ORM\Column(type: 'integer')]
    private int $totalRatings = 0;





    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "recipes")]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Diet::class, inversedBy: 'recipes')]
    private Collection $diets;

    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'recipes')]
    private Collection $allergens;

  

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->diets = new ArrayCollection();
        $this->allergens = new ArrayCollection();
    }


    public function addAllergen(Allergen $allergen): self
{
    if (!$this->allergens->contains($allergen)) {
        $this->allergens[] = $allergen;
        $allergen->addRecipe($this);
    }

    return $this;
}

public function removeAllergen(Allergen $allergen): self
{
    if ($this->allergens->contains($allergen)) {
        $this->allergens->removeElement($allergen);
        $allergen->removeRecipe($this);
    }

    return $this;
}
    

    public function addDiet(Diet $diet): self
    {
        if (!$this->diets->contains($diet)) {
            $this->diets[] = $diet;
            $diet->addRecipe($this);
        }
    
        return $this;
    }
    
    public function removeDiet(Diet $diet): self
    {
        if ($this->diets->contains($diet)) {
            $this->diets->removeElement($diet);
            $diet->removeRecipe($this);
        }
    
        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRecipe($this);
        }
    
        return $this;
    }
    
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRecipe($this);
        }
    
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of ingredients
     */
    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    /**
     * Set the value of ingredients
     */
    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * Get the value of preparation
     */
    public function getPreparation(): ?string
    {
        return $this->preparation;
    }

    /**
     * Set the value of preparation
     */
    public function setPreparation(?string $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * Get the value of cooking_time
     */
    public function getCookingTime(): ?int
    {
        return $this->cooking_time;
    }

    /**
     * Set the value of cooking_time
     */
    public function setCookingTime(?int $cooking_time): self
    {
        $this->cooking_time = $cooking_time;

        return $this;
    }

    /**
     * Get the value of preparation_time
     */
    public function getPreparationTime(): ?int
    {
        return $this->preparation_time;
    }

    /**
     * Set the value of preparation_time
     */
    public function setPreparationTime(?int $preparation_time): self
    {
        $this->preparation_time = $preparation_time;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the value of allergens
     */
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    /**
     * Set the value of allergens
     */
    public function setAllergens(Collection $allergens): self
    {
        $this->allergens = $allergens;

        return $this;
    }

    /**
     * Get the value of diets
     */
    public function getDiets(): Collection
    {
        return $this->diets;
    }

    /**
     * Set the value of diets
     */
    public function setDiets(Collection $diets): self
    {
        $this->diets = $diets;

        return $this;
    }

    public function getIsOnlyAccessibleToPatients(): bool
    {
        return $this->isOnlyAccessibleToPatients;
    }

    public function setIsOnlyAccessibleToPatients(bool $isOnlyAccessibleToPatients): self
    {
        $this->isOnlyAccessibleToPatients = $isOnlyAccessibleToPatients;

        return $this;
    }

   
    /**
     * Get the value of note
     */
    public function getNote(): ?float
    {
        return $this->note;
    }

    /**
     * Set the value of note
     */
    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the value of totalRatings
     */
    public function getTotalRatings(): int
    {
        return $this->totalRatings;
    }

    /**
     * Set the value of totalRatings
     */
    public function setTotalRatings(int $totalRatings): self
    {
        $this->totalRatings = $totalRatings;

        return $this;
    }

    public function getUsers(): Collection
{
    return $this->users;
}


}