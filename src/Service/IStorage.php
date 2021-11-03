<?php

namespace App\Service;

interface IStorage
{
    public function store($data): void;
    public function get($id = null);
}