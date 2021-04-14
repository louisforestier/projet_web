<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table (name="im2021_produits")
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libellé est obligatoire.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le prix est obligatoire.")
     * @Assert\PositiveOrZero(message="Le prix doit être supérieur ou égal à 0.")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="La quantité est obligatoire.")
     * @Assert\PositiveOrZero(message="La quantité doit être supérieure ou égale à 0.")
     */
    private $quantite;


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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }


    /**
     * Produit constructor.
     */
    public function __construct(){
        $this->quantite = null;
    }

}

//Clementine Guillot et Louis Forestier