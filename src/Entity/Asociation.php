<?php

namespace TDW\ACiencia\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "associations")]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $name;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $url;

    #[ORM\ManyToMany(targetEntity: Entity::class)]
    #[ORM\JoinTable(name: "association_entity")]
    private Collection $entities;

    public function __construct(string $name, ?string $url = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->entities = new ArrayCollection();
    }

    // Getters y setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getEntities(): Collection
    {
        return $this->entities;
    }

    public function addEntity(Entity $entity): void
    {
        if (!$this->entities->contains($entity)) {
            $this->entities->add($entity);
        }
    }

    public function removeEntity(Entity $entity): void
    {
        $this->entities->removeElement($entity);
    }
}
