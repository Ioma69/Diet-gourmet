<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity("email", message: "Le nom d'utilisateur est déjà pris...")]
#[UniqueEntity("password", message: "Le mot de passe est déjà pris...")]
#[UniqueEntity("phone", message: "Le numéro est déjà attribué...")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    private ?string $confirm = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private string $phone;


    
    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "user_recipes")]
    private Collection $recipes;
    
    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "user_allergens")]
    private Collection $allergens;
    
    #[ORM\ManyToMany(targetEntity: Diet::class, inversedBy: "users")]
    #[ORM\JoinTable(name: "user_diets")]
    private Collection $diets;
   


    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
        $this->recipes = new ArrayCollection();
        $this->allergens = new ArrayCollection();
        $this->diets = new ArrayCollection();
    }



    public function addRecipe(Recipe $recipe): self
{
    if (!$this->recipes->contains($recipe)) {
        $this->recipes[] = $recipe;
        $recipe->addUser($this);
    }

    return $this;
}

public function removeRecipe(Recipe $recipe): self
{
    if ($this->recipes->contains($recipe)) {
        $this->recipes->removeElement($recipe);
        $recipe->removeUser($this);
    }

    return $this;
}


public function addAllergen(Allergen $allergen): self
{
    if (!$this->allergens->contains($allergen)) {
        $this->allergens[] = $allergen;
        $allergen->addUser($this);
    }

    return $this;
}

public function removeAllergen(Allergen $allergen): self
{
    if ($this->allergens->contains($allergen)) {
        $this->allergens->removeElement($allergen);
        $allergen->removeUser($this);
    }

    return $this;
}
       
public function addDiet(Diet $diet): self
{
    if (!$this->diets->contains($diet)) {
        $this->diets[] = $diet;
        $diet->addUser($this);
    }

    return $this;
}

public function removeDiet(Diet $diet): self
{
    if ($this->diets->contains($diet)) {
        $this->diets->removeElement($diet);
        $diet->removeUser($this);
    }

    return $this;
}



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $this->passwordHasher->hashPassword($this, $password);

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
     * Get the value of firstname
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

   

    /**
     * Get the value of phone
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of confirm
     */
    public function getConfirm(): ?string
    {
        return $this->confirm;
    }

    /**
     * Set the value of confirm
     */
    public function setConfirm(?string $confirm): self
    {
        $this->confirm = $confirm;

        return $this;
    }


    // Récupérer les allergenes et les types de régimes
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }
    
    public function getDiets(): Collection
    {
        return $this->diets;
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