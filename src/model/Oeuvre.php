<?php

namespace GlimpsGoneV2\model;

use DateTime;

class Oeuvre
{
    private ?int $id;
    private string $titre;
    private string $description;
    private DateTime $dateCreation;
    private int $compteurJaime;
    private int $compteurJaimePas;
    private Artiste $artiste;

    public function __construct(?int $id, string $titre, string $description, DateTime $dateCreation, int $compteurJaime, int $compteurJaimePas, Artiste $artiste)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->compteurJaime = $compteurJaime;
        $this->compteurJaimePas = $compteurJaimePas;
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

    public function getCompteurJaime(): int
    {
        return $this->compteurJaime;
    }

    public function getCompteurJaimePas(): int
    {
        return $this->compteurJaimePas;
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
            'dateCreation' => $this->getDateCreation()->format('Y-m-d'),
            'compteurJaime' => $this->getCompteurJaime(),
            'compteurJaimePas' => $this->getCompteurJaimePas(),
            'artiste' => $this->getArtiste()->toArray()
        ];
    }
}
