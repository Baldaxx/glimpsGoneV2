<?php

// Ce code définit une classe PHP nommée Oeuvre qui représente une œuvre d'art. Elle contient des propriétés telles que l'identifiant, le titre, la description, la date de création, le nombre de likes et le nombre de dislikes. La classe fournit des méthodes pour accéder et modifier ces propriétés.

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

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getCompteurJaime(): int
    {
        return $this->compteurJaime;
    }

    public function setCompteurJaime(int $compteurJaime): void
    {
        $this->compteurJaime = $compteurJaime;
    }

    public function getCompteurJaimePas(): int
    {
        return $this->compteurJaimePas;
    }

    public function setCompteurJaimePas(int $compteurJaimePas): void
    {
        $this->compteurJaimePas = $compteurJaimePas;
    }

    public function getArtiste(): Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(Artiste $artiste): void
    {
        $this->artiste = $artiste;
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
            "artiste_nom" => $this->getArtiste()->getName(),
        ];
    }
}
