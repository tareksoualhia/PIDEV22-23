<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur", indexes={@ORM\Index(name="abonnement_id", columns={"abonnement_id"}), @ORM\Index(name="equipe_id", columns={"equipe_id"})})
 * @ORM\Entity
 */
class Joueur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="motdepasse", type="string", length=255, nullable=false)
     */
    private $motdepasse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role_prefere", type="string", length=20, nullable=true)
     */
    private $rolePrefere;

    /**
     * @var \Abonnement
     *
     * @ORM\ManyToOne(targetEntity="Abonnement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abonnement_id", referencedColumnName="id")
     * })
     */
    private $abonnement;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipe_id", referencedColumnName="id")
     * })
     */
    private $equipe;
     /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nom
     *
     * @return  string
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @param  string  $nom
     *
     * @return  self
     */ 
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
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
    public function getMotdepasse(): ?string
{
    return $this->motdepasse;
}

public function setMotdepasse(string $motdepasse): self
{
    $this->motdepasse = $motdepasse;
    return $this;
}

public function getTelephone(): ?string
{
    return $this->telephone;
}

public function setTelephone(?string $telephone): self
{
    $this->telephone = $telephone;
    return $this;
}

public function getRolePrefere(): ?string
{
    return $this->rolePrefere;
}

public function setRolePrefere(?string $rolePrefere): self
{
    $this->rolePrefere = $rolePrefere;
    return $this;
}
public function getAbonnement(): ?Abonnement
{
    return $this->abonnement;
}

public function setAbonnement(?Abonnement $abonnement): self
{
    $this->abonnement = $abonnement;

    return $this;
}

public function getEquipe(): ?Equipe
{
    return $this->equipe;
}

public function setEquipe(?Equipe $equipe): self
{
    $this->equipe = $equipe;

    return $this;
}
public function __toString(): string
{
    return $this->id;
}


}
