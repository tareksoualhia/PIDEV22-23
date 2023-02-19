<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sponsor
 *
 * @ORM\Table(name="sponsor")
 * @ORM\Entity
 */
class Sponsor
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
     * @ORM\Column(name="budget", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $budget;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Evenement", mappedBy="sponsor")
     */
    private $evenement = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evenement = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
