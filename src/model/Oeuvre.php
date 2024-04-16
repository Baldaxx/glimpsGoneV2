<?php

namespace GlimpsGoneV2\model;

use DateTime;

class Oeuvre
{
    private int|null $id;
    private string $titre;
    private string $description;
    private DateTime $dateCreation;
    private int $compteurJaime;
    private int $compteurJaimePas;

    /**
     * Constructeur de l'oeuvre.
     * @param int|null $id
     * @param string $titre
     * @param string $description
     * @param DateTime $dateCreation
     * @param int $compteurJaime
     * @param int $compteurJaimePas
     */
    public function __construct(?int $id, string $titre, string $description, DateTime $dateCreation, int $compteurJaime, int $compteurJaimePas)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->compteurJaime = $compteurJaime;
        $this->compteurJaimePas = $compteurJaimePas;
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

    public function getAnneeCreation(): string
    {
        return $this->dateCreation->format("Y");
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
}
