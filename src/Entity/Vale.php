<?php

namespace App\Entity;

use App\Repository\ValeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ValeRepository::class)
 */

/**
 * @ORM\Entity
 */

class Vale
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vale;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="vale", cascade={"persist"})
     */
    private $solicitudes;

    /**
     * @ORM\Column(type="date")
     */
    private $fechavale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $factura;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechafactura;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    public function __toString(){
        return $this->vale;
    }

    public function __construct()
    {
        $this->solicitudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVale(): ?string
    {
        return $this->vale;
    }

    public function setVale(string $vale): self
    {
        $this->vale = $vale;

        return $this;
    }

    /**
     * @return Collection<int, Solicitud>
     */
    public function getSolicitudes(): Collection
    {
        return $this->solicitudes;
    }

    public function addSolicitude(Solicitud $solicitude): self
    {

        if (!$this->solicitudes->contains($solicitude)) {
            $this->solicitudes[] = $solicitude;
            $solicitude->setVale($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getVale() === $this) {
                $solicitude->setVale(null);
            }
        }

        return $this;
    }

    public function getFechavale(): ?\DateTimeInterface
    {
        return $this->fechavale;
    }

    public function setFechavale(\DateTimeInterface $fechavale): self
    {
        $this->fechavale = $fechavale;

        return $this;
    }

    public function getFactura(): ?string
    {
        return $this->factura;
    }

    public function setFactura(?string $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    public function getFechafactura(): ?\DateTimeInterface
    {
        return $this->fechafactura;
    }

    public function setFechafactura(?\DateTimeInterface $fechafactura): self
    {
        $this->fechafactura = $fechafactura;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

}
