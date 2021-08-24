<?php

namespace App\Entity;

use App\Repository\PokemonTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonTeamRepository::class)
 */
class PokemonTeam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $base_experience;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sprite_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $abilities;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $types;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="pokemons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idTeam;

    public function __construct($name, $base_experience, $sprite_image,$abilities,$types,$idTeam) {
        $this->name = $name;
        $this->base_experience = $base_experience;
        $this->sprite_image = $sprite_image;
        $this->abilities = $abilities;
        $this->types = $types;
        $this->idTeam = $idTeam;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBaseExperience(): ?int
    {
        return $this->base_experience;
    }

    public function setBaseExperience(int $base_experience): self
    {
        $this->base_experience = $base_experience;

        return $this;
    }

    public function getSpriteImage(): ?string
    {
        return $this->sprite_image;
    }

    public function setSpriteImage(string $sprite_image): self
    {
        $this->sprite_image = $sprite_image;

        return $this;
    }

    public function getAbilities(): ?string
    {
        return $this->abilities;
    }

    public function setAbilities(string $abilities): self
    {
        $this->abilities = $abilities;

        return $this;
    }

    public function getTypes(): ?string
    {
        return $this->types;
    }

    public function setTypes(string $types): self
    {
        $this->types = $types;

        return $this;
    }

    public function getIdTeam(): ?Team
    {
        return $this->idTeam;
    }

    public function setIdTeam(?Team $idTeam): self
    {
        $this->idTeam = $idTeam;

        return $this;
    }
}
