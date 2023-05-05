<?php

namespace App\Entity;

use App\Repository\ProduktRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduktRepository::class)]
class Produkt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?float $Preis = null;

    #[ORM\Column]
    private ?int $Bestand = null;

    #[ORM\OneToMany(mappedBy: 'produkt', targetEntity: Kommentare::class, orphanRemoval: true)]
    private Collection $Kommentare;

    public function __construct()
    {
        $this->Kommentare = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPreis(): ?float
    {
        return $this->Preis;
    }

    public function setPreis(float $Preis): self
    {
        $this->Preis = $Preis;

        return $this;
    }

    public function getBestand(): ?int
    {
        return $this->Bestand;
    }

    public function setBestand(int $Bestand): self
    {
        $this->Bestand = $Bestand;

        return $this;
    }

    /**
     * @return Collection<int, Kommentare>
     */
    public function getKommentare(): Collection
    {
        return $this->Kommentare;
    }

    public function addKommentare(Kommentare $kommentare): self
    {
        if (!$this->Kommentare->contains($kommentare)) {
            $this->Kommentare->add($kommentare);
            $kommentare->setProdukt($this);
        }

        return $this;
    }

    public function removeKommentare(Kommentare $kommentare): self
    {
        if ($this->Kommentare->removeElement($kommentare)) {
            // set the owning side to null (unless already changed)
            if ($kommentare->getProdukt() === $this) {
                $kommentare->setProdukt(null);
            }
        }

        return $this;
    }
}
