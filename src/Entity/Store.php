<?php

namespace App\Entity;

use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StoreRepository::class)
 */
class Store
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * Collection: products in this store
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="store", fetch="EAGER")
     */
    private Collection $inventory;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->inventory = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInventory(): Collection
    {
        return $this->inventory;
    }
}
