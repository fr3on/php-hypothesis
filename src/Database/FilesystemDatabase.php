<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Database;

/**
 * Default database implementation using the local filesystem.
 */
class FilesystemDatabase implements ExampleDatabase
{
    private string $baseDir;

    public function __construct(?string $baseDir = null)
    {
        $this->baseDir = $baseDir ?? getcwd() . '/.hypothesis';

        if (!is_dir($this->baseDir)) {
            mkdir($this->baseDir, 0777, true);
        }
    }

    /**
     * @param string $key Unique key for the test property
     * @param array<int, mixed> $example The minimal failing example
     */
    public function save(string $key, array $example): void
    {
        $path = $this->getPath($key);
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($path, serialize($example));
    }

    public function load(string $key): ?array
    {
        $path = $this->getPath($key);

        if (!file_exists($path)) {
            return null;
        }

        $content = file_get_contents($path);
        
        if ($content === false) {
            return null;
        }

        $data = unserialize($content);
        return is_array($data) ? $data : null;
    }

    private function getPath(string $key): string
    {
        // Sanitize key for filesystem
        $safeKey = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $key);
        return $this->baseDir . '/' . $safeKey . '.ex';
    }
}
