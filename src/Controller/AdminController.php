<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Store;
use App\Entity\Inventory;
use App\Service\IStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addStoresAndProducts(Request $request, IStorage $storage): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $productDictionary = [];

        try {
            foreach ($data['products'] as $productData) {
                $product = new Product($productData['sdk'], $productData['sku']);
                $productDictionary[$product->getSdk()] = $product;

                $this->entityManager->persist($product);
            }

            foreach ($data['stores'] as $inventory) {
                $store = new Store($inventory['name']);
                $this->entityManager->persist($store);

                foreach ($inventory['products'] as $inventoryData) {
                    $inventory = new Inventory(
                        $productDictionary[$inventoryData['sdk']],
                        $store,
                        $inventoryData['quantity']
                    );

                    $this->entityManager->persist($inventory);
                }
            }
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage(), 500]);
        }

        $storage->store($data['priorityList']);
        $this->entityManager->flush();

        return $this->json(['result' => true]);
    }

    public function clearAll(): JsonResponse
    {
        $connection = $this->entityManager->getConnection();
        try {
            $connection->executeQuery('DELETE FROM INVENTORY');
            $connection->executeQuery('DELETE FROM PRODUCT');
            $connection->executeQuery('DELETE FROM STORE');
        } catch (\Exception $exception) {
            return $this->json(['result' => false, 'error' => $exception->getMessage()]);
        }

        return $this->json(['result' => true]);
    }
}