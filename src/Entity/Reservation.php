<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $sujet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dat", type="date", nullable=false)
     */
    private $dat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     */
    private $heure;

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
     * @var \Joueur
     *
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="joueur_id", referencedColumnName="id")
     * })
     */
    private $joueur;

    /**
     * @var \Entraineur
     *
     * @ORM\ManyToOne(targetEntity="Entraineur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entraineur_id", referencedColumnName="ld")
     * })
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

}
