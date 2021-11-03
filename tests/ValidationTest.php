<?php

namespace App\Tests;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    /**
     * @dataProvider productSuccessProvider
     */
    public function testProductSuccess(array $input)
    {
        new Product($input['sdk'], $input['sku']);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider productFailureProvider
     */
    public function testProductFailure(array $input)
    {
        $this->expectException(\Exception::class);
        new Product($input['sdk'], $input['sku']);
    }

    public function productSuccessProvider(): array
    {
        return [
            [
                [
                    "sdk" => 992994,
                    "sku" => "99999199811"
                ]
            ],
            [
                [
                    "sdk" => 992991,
                    "sku" => "99999199800"
                ]
            ]
        ];
    }

    public function productFailureProvider(): array
    {
        return [
            [
                [
                    "sdk" => 992994,
                    "sku" => "9999919981a"
                ]
            ],
            [
                [
                    "sdk" => 992991,
                    "sku" => "99999100"
                ]
            ]
        ];
    }
}