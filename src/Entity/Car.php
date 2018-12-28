<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le modèle ne peut être vide !")
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @Assert\NotBlank(message="Le prix ne peut être vide !")
     * @Assert\LessThan(
     *     value = 6000, message="Maximum 6000"
     * )
     * @Assert\GreaterThan(
     *     value = 100, message="Minimum 100"
     * )
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\JoinColumn(name="image_id", nullable=true)
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     *
     * 1 Objet de type Car a plusieurs Keyword
     * @ORM\OneToMany(targetEntity="Keyword", mappedBy="car", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $keywords;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\City", inversedBy="cars")
     */
    private $cities;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $carburant;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="cars")
     */
    private $user;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->cities = new ArrayCollection();
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword)
    {
        $this->keywords->add($keyword);
        $keyword->setCar($this);
    }

    public function removeKeyword(Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return Collection|City[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
