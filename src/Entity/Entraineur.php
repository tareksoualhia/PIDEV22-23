<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entraineur
 *
 * @ORM\Table(name="entraineur")
 * @ORM\Entity
 */
class Entraineur
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="horaire_disponibilite", type="string", length=255, nullable=true)
     */
    private $horaireDisponibilite;

    /**
     * @var \Joueur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ld", referencedColumnName="id")
     * })
     */
    private $ld;

    public function getHoraireDisponibilite(): ?string
    {
        return $this->horaireDisponibilite;
    }

    public function setHoraireDisponibilite(?string $horaireDisponibilite): self
    {
        $this->horaireDisponibilite = $horaireDisponibilite;

        return $this;
    }

    public function getLd(): ?Joueur
    {
        return $this->ld;
    }

    public function setLd(Joueur $ld): self
    {
        $this->ld = $ld;

        return $this;
    }
    public function __toString(): string
{
    return $this->ld;
}
}