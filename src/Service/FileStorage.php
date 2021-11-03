<?php

namespace App\Service;

use Exception;

/**
 * stores array of data as php file
 */
class FileStorage implements IStorage
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @throws Exception
     */
    public function store($data): void
    {
        if (is_array($data)) {
            $file = fopen($this->filename, 'w');
            fwrite($file, "<?php\nreturn\n[\n");
            array_walk($data, fn($item) => fwrite($file, "'$item',\n"));
            fwrite($file, "];\n");

            fclose($file);
        } else {
            throw new Exception('only array storing available');
        }
    }

    public function get($id = null): array
    {
        return require $this->filename;
    }
}