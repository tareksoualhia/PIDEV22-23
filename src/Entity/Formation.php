<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères.")
     */
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description est obligatoire.")
     * @Assert\Regex(
     *     pattern="/^[a-z0-9\s]+$/",
     *     message="La description ne doit contenir que des lettres minuscules, des chiffres et des espaces."
     * )
     */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $meet = null;

    #[ORM\Column]
     /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Le prix est obligatoire.")
     * @Assert\Regex(
     *     pattern="/^[0-9]+$/",
     *     message="Le prix doit être un entier positif."
     * )
     * @Assert\GreaterThanOrEqual(value=0, message="Le prix doit être positif.")
     */
    private ?int $prix = null;

    #[ORM\ManyToOne(inversedBy: 'formation')]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getMeet(): ?string
    {
        return $this->meet;
    }

    public function setMeet(string $meet): self
    {
        $this->meet = $meet;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
