<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use DateTime;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation  
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    




    
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

    #[ORM\Column(length: 255,type:"date")]
    private ?DateTime $Date = null;

    #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: Reponse::class, cascade:["remove"])]  
    #[Exclude()]

    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getDate(): ?DateTime
    {
        return $this->Date;
    }

    public function setDate(DateTime $Date): self
    {
        $this->Date = $Date;

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
