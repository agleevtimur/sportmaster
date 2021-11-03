<?php

namespace App\Entity\Validation;

use Exception;

class ProductValidation implements IValidation
{
    public function validate(array $data): void
    {
        if (strlen($data['sku']) !== 11 || !is_numeric($data['sku'])) {
            throw new Exception("product: incorrect sku value: {$data['sku']}");
        }
    }
}