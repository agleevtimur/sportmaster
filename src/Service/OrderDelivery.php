<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Inventory;
use App\Repository\ProductRepository;


class OrderDelivery
{
    private IDistributionStrategy $strategy;
    private IStorage $storage;
    private ProductRepository $repository;

    public function __construct(IStorage $storage, ProductRepository $repository, IDistributionStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->repository = $repository;
        $this->storage = $storage;
    }

    public function resolve(array $products): array
    {
        $productsSdk = array_map(fn($product) => $product->getSdk(), $products);
        $priorityList = $this->storage->get();
        $products = $this->repository->findBy(['sdk' => $productsSdk]);

        $storeDictionary = [];

        foreach($products as $product) {
            /** @var Inventory $storeProduct */
            foreach ($product->getInventory() as $storeProduct) {
                $storeDictionary[$storeProduct->getStore()->getName()][] = $product->getSdk();
            }
        }

        return $this->strategy->distribute($priorityList, $storeDictionary, $productsSdk);
    }
}