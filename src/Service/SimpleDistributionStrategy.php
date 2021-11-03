<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Inventory;

class SimpleDistributionStrategy implements IDistributionStrategy
{

    public function distribute(array $priorityList, array $storeDictionary, array $cart): array
    {
        $priorityList = array_flip($priorityList);
        uksort($storeDictionary, fn($item1, $item2) => $priorityList[$item1] - $priorityList[$item2]);
        $result = [];

        foreach ($cart as $cartProduct) {
            foreach ($storeDictionary as $store => $products) {
                if (array_intersect($cart, $products) === []) {
                    continue;
                }

                foreach ($products as $index => $product) {
                    if (!in_array($product, $cart)) {
                        continue;
                    }

                    $sharedProductStores = $this->getSharedProductStores($storeDictionary, $product, $products);
                    $shippingStore = $this->resolveShippingStore($sharedProductStores, $result) ?? $store;
                    $result[$shippingStore][] = $product;

                    $index = array_search($product, $cart);
                    unset($cart[$index]);
                }
            }
        }

        foreach ($cart as $product) {
            $result['unknown'][] = $product;
        }

        return $result;
    }

    private function getSharedProductStores(array $otherStoreDictionary, string $product, array $products): array
    {
        $sharedProductStores = [];
        foreach ($otherStoreDictionary as $otherStore => $otherProducts) {
            if (!in_array($product, $otherProducts)) {
                continue;
            }
            $sharedProducts = array_intersect($products, $otherProducts);
            if (count($sharedProducts) > 1) {
                $sharedProductStores[] = $otherStore;
            }
        }

        return $sharedProductStores;
    }

    private function resolveShippingStore(array $sharedProductStores, array $resolvedSores): ?string
    {
        if (count($sharedProductStores) > 1) {
            $min = 100000;
            $key = '';
            $equal = true;
            foreach ($sharedProductStores as $i => $store) {
                $count = count($resolvedSores[$store] ?? []);

                if ($i > 0 && $equal === true && $min !== $count) {
                    $equal = false;
                }

                if ($count < $min) {
                    $min = $count;
                    $key = $store;
                }
            }

            // if each store has same products quantity already, then result store being resolved randomly.
            // Otherwise, result store is the one with fewest products (load distribution, my personal initiation)
            if ($equal === true) {
                $rand = mt_rand(0, count($sharedProductStores) - 1);
                $key = $sharedProductStores[$rand];
            }

            return $key;
        }

        return null;
    }
}