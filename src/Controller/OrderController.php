<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\OrderDelivery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    public function placeOrder(Request $request, OrderDelivery $orderPlacer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $products = [];

        try {
            foreach ($data as $product) {
                $products[] = new Product($product['sdk'], $product['sku']);
            }
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }

        $result = $orderPlacer->resolve($products);

        return $this->json($result);
    }
}