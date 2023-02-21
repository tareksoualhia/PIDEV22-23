<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="entraineur_id", columns={"entraineur_id"}), @ORM\Index(name="abonnement_id", columns={"abonnement_id"}), @ORM\Index(name="joueur_id", columns={"joueur_id"})})
 * @ORM\Entity
 */
class Reservation
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
 * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
 * @Assert\NotBlank
 * @Assert\Length(min=5, max=255)
 */
private $sujet;

    /**
 * @var \DateTime
 *
 * @ORM\Column(name="dat", type="date", nullable=false)
 * @Assert\NotBlank
 * @Assert\Type("\DateTimeInterface")
 * @Assert\GreaterThanOrEqual("today")
 */
private $dat;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     */
    private $heure;


    /**
     * @var \Abonnement
     *
     * @ORM\ManyToOne(targetEntity="Abonnement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="abonnement_id", referencedColumnName="id")
     * })
     * @Assert\NotNull
     */
    private $abonnement;

    /**
     * @var \Joueur
     *
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="joueur_id", referencedColumnName="id")
     * })
     * @Assert\NotNull
     */
    private $joueur;

    /**
     * @var \Entraineur
     *
     * @ORM\ManyToOne(targetEntity="Entraineur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entraineur_id", referencedColumnName="ld")
     * })
     * @Assert\NotNull
     */
    private $entraineur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Abonnement", mappedBy="idReservation")
     */
    private $idAbonnement = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAbonnement = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDat(): ?\DateTimeInterface
    {
        return $this->dat;
    }

    public function setDat(\DateTimeInterface $dat): self
    {
        $this->dat = $dat;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

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

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getEntraineur(): ?Entraineur
    {
        return $this->entraineur;
    }

    public function setEntraineur(?Entraineur $entraineur): self
    {
        $this->entraineur = $entraineur;

        return $this;
    }

    /**
    * Get the value of idAbonnement
    *
    * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdAbonnement()
    {
      return $this->idAbonnement;
    }

    public function addIdAbonnement(Abonnement $idAbonnement): self
    {
        if (!$this->idAbonnement->contains($idAbonnement)) {
            $this->idAbonnement[] = $idAbonnement;
            $idAbonnement->addIdReservation($this);
        }

        return $this;
    }

    public function removeIdAbonnement(Abonnement $idAbonnement): self
    {
        if ($this->idAbonnement->contains($idAbonnement)) {
            $this->idAbonnement->removeElement($idAbonnement);
            $idAbonnement->removeIdReservation($this);
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->id;
    }
}
