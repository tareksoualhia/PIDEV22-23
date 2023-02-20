<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity
 */
class Abonnement
{
    const TYPE_BASIC = 'basic';
    const TYPE_AVANCE = 'avance';
    const TYPE_PRO = 'pro';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
     /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type = null;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     * @Assert\Choice(choices={"PRO", "basic", "avancé"})
     */
    private $titre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\Choice(choices={10.0, 30.0, 50.0})
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=false)
     * @Assert\Choice(choices={30, 45, 80})
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau_access", type="string", length=255, nullable=false)
     * @Assert\Choice(choices={"intermediate","beginner", "	advanced"})
     */
    private $niveauAccess;

    /**
     * @var int
     *
     * @ORM\Column(name="reservations_total", type="integer", nullable=false)
     * @Assert\Choice(choices={20, 4, 8})
     */
    private $reservationsTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="reservations_restantes", type="integer", nullable=false)
     * @Assert\Range(min=0, maxPropertyPath="reservationsTotal")
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
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
    
        switch ($type) {
            case self::TYPE_BASIC:
                $this->setTitre('basic');
                $this->setPrix(10);
                $this->setDuree(30);
                $this->setNiveauAccess('beginner');
                $this->setReservationsTotal(4);
                $this->setReservationsRestantes(4);
                break;
            case self::TYPE_AVANCE:
                $this->setTitre('avancé');
                $this->setPrix(30);
                $this->setDuree(45);
                $this->setNiveauAccess('intermediate');
                $this->setReservationsTotal(8);
                $this->setReservationsRestantes(8);
                break;
            case self::TYPE_PRO:
                $this->setTitre('PRO');
                $this->setPrix(50);
                $this->setDuree(80);
                $this->setNiveauAccess('advanced');
                $this->setReservationsTotal(20);
                $this->setReservationsRestantes(20);
                break;
            default:
                throw new \InvalidArgumentException('Invalid subscription type');
        }
    
        return $this;
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
    public function setIdReservation(int $idReservation): self
    {
        $this->idReservation = $idReservation;
    
        return $this;
    }
    public function __toString()
   {
     return $this->id;
   }

}
