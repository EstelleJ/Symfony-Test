<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
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
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="villes")
     */
    private $departement;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Coiffeur::class, mappedBy="ville")
     */
    private $coiffeurs;

    public function __construct()
    {
        $this->coiffeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->titre;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Coiffeur[]
     */
    public function getCoiffeurs(): Collection
    {
        return $this->coiffeurs;
    }

    public function addCoiffeur(Coiffeur $coiffeur): self
    {
        if (!$this->coiffeurs->contains($coiffeur)) {
            $this->coiffeurs[] = $coiffeur;
            $coiffeur->setVille($this);
        }

        return $this;
    }

    public function removeCoiffeur(Coiffeur $coiffeur): self
    {
        if ($this->coiffeurs->removeElement($coiffeur)) {
            // set the owning side to null (unless already changed)
            if ($coiffeur->getVille() === $this) {
                $coiffeur->setVille(null);
            }
        }

        return $this;
    }

}
