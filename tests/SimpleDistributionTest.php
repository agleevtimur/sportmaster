<?php

namespace App\Tests;

use App\Service\SimpleDistributionStrategy;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SimpleDistributionTest extends TestCase
{
    /**
     * @dataProvider algorithmDataProvider
     */
    public function testAlgorithm(array $input, array $expectedResult): void
    {
        $storeDictionary = [];
        foreach ($input['stores'] as $store) {
            foreach ($store['inventory'] as $sdk) {
                $storeDictionary[$store['name']][] = $sdk;
            }
        }

        $actualResult = (new SimpleDistributionStrategy())->distribute($input['priorityList'], $storeDictionary, $input['cart']);

        if (isset($expectedResult['result1'])) {
            $this->checkMultiplePossibleResult($actualResult, $expectedResult);
        } else {
            $this->assertEquals($expectedResult, $actualResult);
        }
    }

    /**
     * @dataProvider getSharedProductStoresProvider
     */
    public function testGetSharedProductStores(array $input, $expectedResult): void
    {
        $class = new ReflectionClass(SimpleDistributionStrategy::class);
        $method = $class->getMethod('getSharedProductStores');
        $method->setAccessible(true);

        $actualResult = $method->invoke(
            new SimpleDistributionStrategy(),
            $input['storeDictionary'],
            $input['product'],
            $input['products']
        );

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider resolveShippingStoreProvider
     */
    public function testResolveShippingStore(array $input, string $expectedResult): void
    {
        $class = new ReflectionClass(SimpleDistributionStrategy::class);
        $method = $class->getMethod('resolveShippingStore');
        $method->setAccessible(true);

        $actualResult = $method->invoke(new SimpleDistributionStrategy(), $input['stores'], $input['resolvedStores']);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function algorithmDataProvider(): array
    {
        return [
            'product is out of store (example1)' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store3',
                            'inventory' => [992996]
                        ],
                    ],
                    'priorityList' => ['store1', 'store2', 'store3'],
                    'cart' => [992994, 993991, 992996]
                ],
                [
                    'store1' => [992994],
                    'store3' => [992996],
                    'unknown' => [993991]
                ]
            ],
            'random store (example2)' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994, 993991]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [992994, 993991]
                        ],
                        [
                            'name' => 'store3',
                            'inventory' => [992996]
                        ],
                    ],
                    'priorityList' => ['store1', 'store2', 'store3'],
                    'cart' => [992994, 993991, 992996]
                ],
                [
                    'result1' => [
                        'store1' => [992994],
                        'store2' => [993991],
                        'store3' => [992996]
                    ],
                    'result2' => [
                        'store1' => [993991],
                        'store2' => [992994],
                        'store3' => [992996]
                    ]
                ]
            ],
            'two same products (example3)' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [992994]
                        ],
                    ],
                    'priorityList' => ['store1', 'store2'],
                    'cart' => [992994, 992994]
                ],
                [
                    'store1' => [992994],
                    'store2' => [992994]
                ]
            ],
            'unsorted priority list' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store3',
                            'inventory' => [992996]
                        ],
                    ],
                    'priorityList' => ['store2', 'store1', 'store3'],
                    'cart' => [992994, 992996]
                ],
                [
                    'store2' => [992994],
                    'store3' => [992996]
                ]
            ],
            'load distribution' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994, 993991, 992996, 888888]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [992994, 993991]
                        ],
                        [
                            'name' => 'store3',
                            'inventory' => [992996, 888888]
                        ],
                    ],
                    'priorityList' => ['store1', 'store2', 'store3'],
                    'cart' => [992994, 993991, 992996, 888888]
                ],
                [
                    'result1' => [
                        'store1' => [992994, 992996],
                        'store2' => [993991],
                        'store3' => [88888]
                    ],
                    'result2' => [
                        'store1' => [993991, 992996],
                        'store2' => [992994],
                        'store3' => [88888]
                    ],
                    'result3' => [
                        'store1' => [992994, 888888],
                        'store2' => [993991],
                        'store3' => [992996]
                    ],
                    'result4' => [
                        'store1' => [993991, 888888],
                        'store2' => [992994],
                        'store3' => [992996]
                    ],
                    'result5' => [
                        'store1' => [992996],
                        'store2' => [993991, 992994],
                        'store3' => [888888]
                    ],
                    'result6' => [
                        'store1' => [888888],
                        'store2' => [992994, 993991],
                        'store3' => [992996]
                    ],
                    'result7' => [
                        'store1' => [992994],
                        'store2' => [993991],
                        'store3' => [992996, 888888]
                    ],
                    'result8' => [
                        'store1' => [993991],
                        'store2' => [992994],
                        'store3' => [992996, 888888]
                    ],
                ]
            ],
            'big cart' => [
                [
                    'stores' => [
                        [
                            'name' => 'store1',
                            'inventory' => [992994]
                        ],
                        [
                            'name' => 'store2',
                            'inventory' => [993991]
                        ],
                        [
                            'name' => 'store3',
                            'inventory' => [992996]
                        ],
                    ],
                    'priorityList' => ['store1', 'store2', 'store3'],
                    'cart' => [992994, 993991, 992996, 992996, 992996, 992996, 992996]
                ],
                [
                    'store1' => [992994],
                    'store2' => [993991],
                    'store3' => [992996, 992996, 992996, 992996, 992996]
                ]
            ]
        ];
    }

    public function getSharedProductStoresProvider(): array
    {
        return [
            'common' => [
                [
                    'storeDictionary' => [
                        'store1' => [992994, 993991],
                        'store2' => [992994, 993991, 888888]
                    ],
                    'products' => [992994, 993991],
                    'product' => 992994
                ],
                ['store1', 'store2']
            ],
            'unknown product' => [
                [
                    'storeDictionary' => [
                        'store1' => [992994, 993991],
                        'store2' => [992994, 993991, 888888]
                    ],
                    'products' => [992994, 993991],
                    'product' => 992995
                ],
                []
            ],
            'less than 2 products shared' => [
                [
                    'storeDictionary' => [
                        'store1' => [992994, 993991],
                        'store2' => [992994, 993991, 888888]
                    ],
                    'products' => [992994, 993992],
                    'product' => 992994
                ],
                []
            ]
        ];
    }

    public function resolveShippingStoreProvider(): array
    {
        return [
            'load distribution' => [
                [
                    'stores' => ['store1', 'store2'],
                    'resolvedStores' => [
                        'store1' => [888888],
                        'store2' => [111111, 999999]
                    ]
                ],
                'store1'
            ]
        ];
    }

    private function checkMultiplePossibleResult(array $actualResult, array $expectedResult)
    {
        $true = false;
        foreach ($expectedResult as $result) {
            $true = $actualResult == $result;
            if ($true) {
                break;
            }
        }

        $this->assertTrue($true);
    }
}