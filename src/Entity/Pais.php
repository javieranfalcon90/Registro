<?php

namespace App\Entity;

use App\Repository\PaisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaisRepository::class)
 */
class Pais
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Solicitante::class, mappedBy="pais")
     */
    private $solicitantes;

    /**
     * @ORM\OneToMany(targetEntity=Fabricante::class, mappedBy="pais")
     */
    private $fabricantes;

    /**
     * @ORM\OneToMany(targetEntity=Solicitud::class, mappedBy="paisfabricante")
     */
    private $solicitudes;

    public function __toString(){
        return $this->nombre;
    }

    public function __construct()
    {
        $this->solicitantes = new ArrayCollection();
        $this->fabricantes = new ArrayCollection();
        $this->solicitudes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Solicitante>
     */
    public function getSolicitantes(): Collection
    {
        return $this->solicitantes;
    }

    public function addSolicitante(Solicitante $solicitante): self
    {
        if (!$this->solicitantes->contains($solicitante)) {
            $this->solicitantes[] = $solicitante;
            $solicitante->setPais($this);
        }

        return $this;
    }

    public function removeSolicitante(Solicitante $solicitante): self
    {
        if ($this->solicitantes->removeElement($solicitante)) {
            // set the owning side to null (unless already changed)
            if ($solicitante->getPais() === $this) {
                $solicitante->setPais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fabricante>
     */
    public function getFabricantes(): Collection
    {
        return $this->fabricantes;
    }

    public function addFabricante(Fabricante $fabricante): self
    {
        if (!$this->fabricantes->contains($fabricante)) {
            $this->fabricantes[] = $fabricante;
            $fabricante->setPais($this);
        }

        return $this;
    }

    public function removeFabricante(Fabricante $fabricante): self
    {
        if ($this->fabricantes->removeElement($fabricante)) {
            // set the owning side to null (unless already changed)
            if ($fabricante->getPais() === $this) {
                $fabricante->setPais(null);
            }
        }

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
            $solicitude->setPaisfabricante($this);
        }

        return $this;
    }

    public function removeSolicitude(Solicitud $solicitude): self
    {
        if ($this->solicitudes->removeElement($solicitude)) {
            // set the owning side to null (unless already changed)
            if ($solicitude->getPaisfabricante() === $this) {
                $solicitude->setPaisfabricante(null);
            }
        }

        return $this;
    }
}
