<?php

namespace GlimpsGoneV2\model;

use DateTime;

class Oeuvre
{
    private ?int $id;
    public string $titre;
    public string $description;
    private DateTime $dateCreation;
    private Artiste $artiste;

    public function __construct(?int $id, string $titre, string $description, DateTime $dateCreation, Artiste $artiste)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->artiste = $artiste;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function getArtiste(): Artiste
    {
        return $this->artiste;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'titre' => $this->getTitre(),
            'description' => $this->getDescription(),
            'dateCreation' => $this->getDateCreation()->getTimestamp(),
            'artiste_nom' => $this->getArtiste()->getNom()
        ];
    }
}
