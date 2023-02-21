<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
     /**
     * @Assert\NotBlank(message=" veuillez saisir votre numero  ")
     * @Assert\Length(
     *      max = 3,
     *      minMessage=" Entrer un titre au max 3 caracteres")
     */
    #[ORM\Column(length: 255)]
    private ?string $Numero = null;

     /**
     * @Assert\NotBlank(message=" Nom doit etre non vide")
     */


    #[ORM\Column(length: 255)]
    private ?string $Nom = null;
     /**
    * @Assert\NotBlank(message="Email ne doit pas etre vide .")
    * @Assert\Email(message="Adresse Email doit etre valide .")
    */

    #[ORM\Column(length: 255)]
    private ?string $Email = null;
        /**
     * @Assert\NotBlank(message=" veuillez choisir le type  ")
     */

    #[ORM\Column(length: 255)]
    private ?string $Type = null;
    /**
     * @Assert\NotBlank(message=" veuillez fournir votre description ")
     */

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: Reponse::class, cascade:["remove"])]   

    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->Numero;
    }

    public function setNumero(string $Numero): self
    {
        $this->Numero = $Numero;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

        return $this;
    }
}
