<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity
 */
class Equipe
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
     * @var string|null
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Competition", inversedBy="equipe")
     * @ORM\JoinTable(name="equipe_competition",
     *   joinColumns={
     *     @ORM\JoinColumn(name="equipe_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   }
     * )
     */
    private $competition = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competition = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
