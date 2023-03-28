<?php

namespace App\Entity;

use App\Repository\EquipesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipesRepository::class)]
class Equipes
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $entraineur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $creneaux = null;

    #[ORM\Column(length: 255)]
    private ?string $url_photo = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $url_result_calendrier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getEntraineur(): ?string
    {
        return $this->entraineur;
    }

    public function setEntraineur(string $entraineur): self
    {
        $this->entraineur = $entraineur;

        return $this;
    }

    public function getCreneaux(): ?string
    {
        return $this->creneaux;
    }

    public function setCreneaux(?string $creneaux): self
    {
        $this->creneaux = $creneaux;

        return $this;
    }

    public function getUrlPhoto(): ?string
    {
        return $this->url_photo;
    }

    public function setUrlPhoto(string $url_photo): self
    {
        $this->url_photo = $url_photo;

        return $this;
    }

    public function getUrlResultCalendrier(): ?string
    {
        return $this->url_result_calendrier;
    }

    public function setUrlResultCalendrier(?string $url_result_calendrier): self
    {
        $this->url_result_calendrier = $url_result_calendrier;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
