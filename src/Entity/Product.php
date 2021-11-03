<?php

namespace App\Entity;

use App\Entity\Validation\IValidation;
use App\Entity\Validation\ProductValidation;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product extends ProductValidation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $sdk;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private string $sku;

    /**
     * Collection: stores which have this product
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="product", fetch="EAGER")
     */
    private Collection $inventory;

    public function __construct(int $sdk, string $sku)
    {
        $this->validate(['sku' => $sku]);
        $this->sdk = $sdk;
        $this->sku = $sku;
        $this->inventory = new ArrayCollection();
    }

    public function getSdk(): int
    {
        return $this->sdk;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getInventory(): Collection
    {
        return $this->inventory;
    }
}
