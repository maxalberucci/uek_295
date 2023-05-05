<?php

namespace App\Entity;

use App\Repository\KommentareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KommentareRepository::class)]
class Kommentare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $Kommentare = null;

    #[ORM\ManyToOne(inversedBy: 'Kommentare')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produkt $produkt = null;

    #[ORM\Column]
    private ?int $Rezensionen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKommentare(): ?string
    {
        return $this->Kommentare;
    }

    public function setKommentare(string $Kommentare): self
    {
        $this->Kommentare = $Kommentare;

        return $this;
    }

    public function getProdukt(): ?Produkt
    {
        return $this->produkt;
    }

    public function setProdukt(?Produkt $produkt): self
    {
        $this->produkt = $produkt;

        return $this;
    }

    public function getRezensionen(): ?int
    {
        return $this->Rezensionen;
    }

    public function setRezensionen(int $Rezensionen): self
    {
        $this->Rezensionen = $Rezensionen;

        return $this;
    }
}
