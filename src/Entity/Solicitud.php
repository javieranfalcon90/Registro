<?php

namespace App\Entity;

use App\Repository\SolicitudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SolicitudRepository::class)
 */

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"codigo", "fechaentrada", "porcd"}, message="No se pudo relizar la operación correctamente porque esta solicitud ya está registrada.")
 */

class Solicitud
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $personacontacto;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="boolean")
     */
    private $muestra;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class, inversedBy="solicitudes")
     */
    private $paisfabricante;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class, inversedBy="solicitudes")
     */
    private $paissolicitante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fortaleza;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class, inversedBy="solicitudes")
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity=Ifa::class, inversedBy="solicitudes")
     */
    private $ifa;

    /**
     * @ORM\ManyToOne(targetEntity=Ff::class, inversedBy="solicitudes")
     */
    private $ff;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="solicitudes")
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity=Solicitante::class, inversedBy="solicitudes")
     */
    private $solicitante;

    /**
     * @ORM\ManyToOne(targetEntity=Fabricante::class, inversedBy="solicitudes")
     */
    private $fabricante;

    /**
     * @ORM\ManyToOne(targetEntity=Clasederiesgo::class, inversedBy="solicitudes")
     */
    private $clasederiesgo;

    /**
     * @ORM\ManyToOne(targetEntity=Tipoproducto::class, inversedBy="solicitudes")
     */
    private $tipoproducto;

    /**
     * @ORM\ManyToOne(targetEntity=Tipotramite::class, inversedBy="solicitudes")
     */
    private $tipotramite;




    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaentrada;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechacierre;





    /**
     * @ORM\ManyToOne(targetEntity=Vale::class, inversedBy="solicitudes")
     * @ORM\JoinColumn(name="vale_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $vale;

    /**
     * @ORM\ManyToOne(targetEntity=Especialista::class, inversedBy="solicitudes")
     */
    private $especialista;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Ls::class, inversedBy="solicitudes")
     * @ORM\JoinColumn(name="ls_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $ls;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pagado;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechapreevaluacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preevaluacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $porcd;

    /**
     * @ORM\ManyToOne(targetEntity=Denominacion::class, inversedBy="solicitudes")
     */
    private $denominacion;

    /**
     * @ORM\ManyToMany(targetEntity=Parteaevaluar::class, inversedBy="solicitudes")
     */
    private $parteaevaluar;

    /**
     * @ORM\ManyToMany(targetEntity=Conclusion::class, inversedBy="solicitudes")
     */
    private $conclusiones;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $iscd;


    public function __toString(){
        return $this->codigo;
    }

    public function __construct()
    {
        $this->parteaevaluar = new ArrayCollection();
        $this->conclusiones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonacontacto(): ?string
    {
        return $this->personacontacto;
    }

    public function setPersonacontacto(?string $personacontacto): self
    {
        $this->personacontacto = $personacontacto;

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

    public function getMuestra(): ?bool
    {
        return $this->muestra;
    }

    public function setMuestra(bool $muestra): self
    {
        $this->muestra = $muestra;

        return $this;
    }

    public function getPaisfabricante(): ?Pais
    {
        return $this->paisfabricante;
    }

    public function setPaisfabricante(?Pais $paisfabricante): self
    {
        $this->paisfabricante = $paisfabricante;

        return $this;
    }

    public function getPaissolicitante(): ?Pais
    {
        return $this->paissolicitante;
    }

    public function setPaissolicitante(?Pais $paissolicitante): self
    {
        $this->paissolicitante = $paissolicitante;

        return $this;
    }

    public function getFortaleza(): ?string
    {
        return $this->fortaleza;
    }

    public function setFortaleza(string $fortaleza): self
    {
        $this->fortaleza = $fortaleza;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getIfa(): ?Ifa
    {
        return $this->ifa;
    }

    public function setIfa(?Ifa $ifa): self
    {
        $this->ifa = $ifa;

        return $this;
    }

    public function getFf(): ?Ff
    {
        return $this->ff;
    }

    public function setFf(?Ff $ff): self
    {
        $this->ff = $ff;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getSolicitante(): ?Solicitante
    {
        return $this->solicitante;
    }

    public function setSolicitante(?Solicitante $solicitante): self
    {
        $this->solicitante = $solicitante;

        return $this;
    }

    public function getFabricante(): ?Fabricante
    {
        return $this->fabricante;
    }

    public function setFabricante(?Fabricante $fabricante): self
    {
        $this->fabricante = $fabricante;

        return $this;
    }

    public function getClasederiesgo(): ?Clasederiesgo
    {
        return $this->clasederiesgo;
    }

    public function setClasederiesgo(?Clasederiesgo $clasederiesgo): self
    {
        $this->clasederiesgo = $clasederiesgo;

        return $this;
    }

    public function getTipoproducto(): ?Tipoproducto
    {
        return $this->tipoproducto;
    }

    public function setTipoproducto(?Tipoproducto $tipoproducto): self
    {
        $this->tipoproducto = $tipoproducto;

        return $this;
    }

    public function getTipotramite(): ?Tipotramite
    {
        return $this->tipotramite;
    }

    public function setTipotramite(?Tipotramite $tipotramite): self
    {
        $this->tipotramite = $tipotramite;

        return $this;
    }

    public function getFechaentrada(): ?\DateTimeInterface
    {
        return $this->fechaentrada;
    }

    public function setFechaentrada(?\DateTimeInterface $fechaentrada): self
    {
        $this->fechaentrada = $fechaentrada;

        return $this;
    }

    public function getFechacierre(): ?\DateTimeInterface
    {
        return $this->fechacierre;
    }

    public function setFechacierre(?\DateTimeInterface $fechacierre): self
    {
        $this->fechacierre = $fechacierre;

        return $this;
    }

    public function getVale(): ?Vale
    {
        return $this->vale;
    }

    public function setVale(?Vale $vale): self
    {
        $this->vale = $vale;

        return $this;
    }

    public function getEspecialista(): ?Especialista
    {
        return $this->especialista;
    }

    public function setEspecialista(?Especialista $especialista): self
    {
        $this->especialista = $especialista;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getLs(): ?Ls
    {
        return $this->ls;
    }

    public function setLs(?Ls $ls): self
    {
        $this->ls = $ls;

        return $this;
    }

    public function getPagado(): ?bool
    {
        return $this->pagado;
    }

    public function setPagado(bool $pagado): self
    {
        $this->pagado = $pagado;

        return $this;
    }

    public function getFechapreevaluacion(): ?\DateTimeInterface
    {
        return $this->fechapreevaluacion;
    }

    public function setFechapreevaluacion(?\DateTimeInterface $fechapreevaluacion): self
    {
        $this->fechapreevaluacion = $fechapreevaluacion;

        return $this;
    }

    public function getPreevaluacion(): ?string
    {
        return $this->preevaluacion;
    }

    public function setPreevaluacion(?string $preevaluacion): self
    {
        $this->preevaluacion = $preevaluacion;

        return $this;
    }

    public function getPorcd(): ?bool
    {
        return $this->porcd;
    }

    public function setPorcd(bool $porcd): self
    {
        $this->porcd = $porcd;

        return $this;
    }

    public function getDenominacion(): ?Denominacion
    {
        return $this->denominacion;
    }

    public function setDenominacion(?Denominacion $denominacion): self
    {
        $this->denominacion = $denominacion;

        return $this;
    }

    /**
     * @return Collection<int, Parteaevaluar>
     */
    public function getParteaevaluar(): Collection
    {
        return $this->parteaevaluar;
    }

    public function addParteaevaluar(Parteaevaluar $parteaevaluar): self
    {
        if (!$this->parteaevaluar->contains($parteaevaluar)) {
            $this->parteaevaluar[] = $parteaevaluar;
        }

        return $this;
    }

    public function removeParteaevaluar(Parteaevaluar $parteaevaluar): self
    {
        $this->parteaevaluar->removeElement($parteaevaluar);

        return $this;
    }

    /**
     * @return Collection<int, Conclusion>
     */
    public function getConclusiones(): Collection
    {
        return $this->conclusiones;
    }

    public function addConclusione(Conclusion $conclusione): self
    {
        if (!$this->conclusiones->contains($conclusione)) {
            $this->conclusiones[] = $conclusione;
            //$conclusione->addSolicitude($this);
        }

        return $this;
    }

    public function removeConclusione(Conclusion $conclusione): self
    {
        if ($this->conclusiones->removeElement($conclusione)) {
            $conclusione->removeSolicitude($this);
        }

        return $this;
    }

    public function getIscd(): ?bool
    {
        return $this->iscd;
    }

    public function setIscd(?bool $iscd): self
    {
        $this->iscd = $iscd;

        return $this;
    }


}
