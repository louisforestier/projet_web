<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="im2021_utilisateurs", options={"comment"="Table des utilisateurs du site"})
 * @ORM\Entity(repositoryClass=UtilisateursRepository::class)
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="pk",type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=30,
     *     unique=true,
     *     options={"comment"="sert de login (doit être unique)"})
     */
    private $identifiant;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=64,
     *     options={"comment"="mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer"}
     * )
     */
    private $motdepasse;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=30,
     *     nullable=true,
     *     options={"default"=null}
     * )
     */
    private $nom;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=30,
     *     nullable=true,
     *     options={"default"=null}
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(
     *     type="date",
     *     nullable=true,
     *     options={"default"=null}
     * )
     */
    private $anniversaire;

    /**
     * @ORM\Column(
     *     type="boolean",
     *     options={"comment"="type booléen","default"=false}
     * )
     */
    private $isadmin;

    /**
     * Utilisateur constructor.
     */
    public function __construct()
    {
        $this->nom = null;
        $this->prenom = null;
        $this->anniversaire = null;
        $this->isadmin = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): self
    {
        $this->motdepasse = sha1($motdepasse);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAnniversaire(): ?\DateTimeInterface
    {
        return $this->anniversaire;
    }

    public function setAnniversaire(?\DateTimeInterface $anniversaire): self
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    public function getIsadmin(): ?bool
    {
        return $this->isadmin;
    }

    public function setIsadmin(bool $isadmin): self
    {
        $this->isadmin = $isadmin;

        return $this;
    }
}
