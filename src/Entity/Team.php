<?php

namespace App\Entity;

use App\Repository\TeamPokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamPokemonRepository::class)
 */
class Team
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
     * @ORM\OneToMany(targetEntity=PokemonTeam::class, mappedBy="idTeam")
     */
    private $pokemons;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
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

    /**
     * @return Collection|PokemonTeam[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(PokemonTeam $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
            $pokemon->setIdTeam($this);
        }

        return $this;
    }

    public function removePokemon(PokemonTeam $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getIdTeam() === $this) {
                $pokemon->setIdTeam(null);
            }
        }

        return $this;
    }

    public function getSprites(): string
    {
        $spritesPokemon = '';
        foreach ($this->pokemons as $p) {
            $spritesPokemon .= "<img style='width:45px;' src='" . $p->getSpriteImage() . "'> ";
        }
        return $spritesPokemon;
    }

    public function getTotExperience(): string
    {
        $TotExperiencePokemon = 0;
        foreach ($this->pokemons as $p) {
            $TotExperiencePokemon += $p->getBaseExperience();
        }
        return $TotExperiencePokemon;
    }

    public function getAllTypes(): string
    {
        $allTypes = '';
        foreach ($this->pokemons as $p) {
            $strObjTypes = explode(", ", $p->getTypes());
            foreach ($strObjTypes as $o) {
                if (!str_contains($allTypes, $o)) {
                    $allTypes .= $o . ", ";
                }
            }
        }
        $allTypes = rtrim($allTypes, ", ");
        return $allTypes;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
