<?php

namespace App\Entity\Validation;

use Exception;

class InventoryValidation implements IValidation
{
    public function validate(array $data): void
    {
        if ($data['quantity'] < 0) {
            throw new Exception("inventory: incorrect quantity: {$data['quantity']}");
        }
    }
}