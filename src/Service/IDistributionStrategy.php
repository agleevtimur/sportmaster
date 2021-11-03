<?php

namespace App\Service;

interface IDistributionStrategy
{
    public function distribute(array $priorityList, array $storeDictionary, array $cart): array;
}