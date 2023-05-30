<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
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
}
