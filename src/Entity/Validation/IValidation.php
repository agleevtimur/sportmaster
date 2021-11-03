<?php

namespace App\Entity\Validation;

interface IValidation
{
    /**
     * @throws \Exception
     */
    public function validate(array $data): void;
}