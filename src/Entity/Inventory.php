<?php

namespace App\Entity;

use App\Entity\Validation\InventoryValidation;
use App\Repository\InventoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InventoryRepository::class)
 */
class Inventory extends InventoryValidation
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="sdk")
     * @ORM\JoinColumn(name="sdk", referencedColumnName="sdk")
     */
    private Product $product;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="id")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
     */
    private Store $store;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $quantity;

    public function __construct(Product $product, Store $store, int $quantity)
    {
        $this->validate(['quantity' => $quantity]);
        $this->product = $product;
        $this->store = $store;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
