<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity
 */
class Abonnement
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=false)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau_access", type="string", length=255, nullable=false)
     */
    private $niveauAccess;

    /**
     * @var int
     *
     * @ORM\Column(name="reservations_total", type="integer", nullable=false)
     */
    private $reservationsTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="reservations_restantes", type="integer", nullable=false)
     */
    private $reservationsRestantes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Reservation", inversedBy="idAbonnement")
     * @ORM\JoinTable(name="abonnement_reservation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_abonnement", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_reservation", referencedColumnName="id")
     *   }
     * )
     */
    private $idReservation = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idReservation = new \Doctrine\Common\Collections\ArrayCollection();
    }
     /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param float $prix
     */
    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return float
     */
    public function getPrix(): ?float
    {
        return $this->prix;
    }

    /**
     * @param int $duree
     */
    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int
     */
    public function getDuree(): ?int
    {
        return $this->duree;
    }
        /**
     * Get the value of niveauAccess
     *
     * @return string
     */
    public function getNiveauAccess()
    {
        return $this->niveauAccess;
    }

    /**
     * Set the value of niveauAccess
     *
     * @param string $niveauAccess
     *
     * @return self
     */
    public function setNiveauAccess($niveauAccess)
    {
        $this->niveauAccess = $niveauAccess;

        return $this;
    }

    /**
     * Get the value of reservationsTotal
     *
     * @return int
     */
    public function getReservationsTotal()
    {
        return $this->reservationsTotal;
    }

    /**
     * Set the value of reservationsTotal
     *
     * @param int $reservationsTotal
     *
     * @return self
     */
    public function setReservationsTotal($reservationsTotal)
    {
        $this->reservationsTotal = $reservationsTotal;

        return $this;
    }

    /**
     * Get the value of reservationsRestantes
     *
     * @return int
     */
    public function getReservationsRestantes()
    {
        return $this->reservationsRestantes;
    }

    /**
     * Set the value of reservationsRestantes
     *
     * @param int $reservationsRestantes
     *
     * @return self
     */
    public function setReservationsRestantes($reservationsRestantes)
    {
        $this->reservationsRestantes = $reservationsRestantes;

        return $this;
    }
     /**
     * Get the reservations associated with this Abonnement
     *
     * @return Collection|Reservation[]
     */
    public function getIdReservation()
    {
        return $this->idReservation;
    }

    /**
     * Set the reservations associated with this Abonnement
     *
     * @param Collection|Reservation[] $idReservation
     * @return $this
     */
    public function setIdReservation(Collection $idReservation): self
    {
        $this->idReservation = $idReservation;
        return $this;
    }

}
