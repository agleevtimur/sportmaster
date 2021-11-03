<?php

namespace App\Tests;

use App\Service\FileStorage;
use PHPUnit\Framework\TestCase;

class FileStorageTest extends TestCase
{
    private const UTIL_DIRECTORY = 'tests/util';

    /**
     * @dataProvider storeTestProvider
     */
    public function testStorage(array $input)
    {
        $storage = new FileStorage($input['filename']);
        $storage->store($input['data']);
        $this->assertEquals($input['data'], $storage->get());
    }

    protected function tearDown(): void
    {
        $files = glob(self::UTIL_DIRECTORY.'/*');
        array_walk($files, fn($file) => unlink($file));
    }

    public function storeTestProvider(): array
    {
        return [
            [
                [
                    'filename' => 'tests/util/test.php',
                    'data' => [1, 2, 3]
                ]
            ]
        ];
    }
}