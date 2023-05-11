<?php

namespace App\DataFixtures;

use App\Entity\Kommentare;
use App\Entity\Produkt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class KommentareFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $produkt = new Produkt();
        $produkt->setName("Produktname TEST");
        $produkt->setBestand(200);
        $produkt->setPreis(20.30);

        $manager->persist($produkt);

        $kommentare = new Kommentare();
        $kommentare->setKommentare("Kommentar");
        $kommentare->setRezensionen(2);
        $kommentare->setProdukt($produkt);

        $manager->persist($kommentare);

        $manager->flush();
    }
}
