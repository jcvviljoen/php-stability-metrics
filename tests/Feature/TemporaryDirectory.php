<?php

declare(strict_types=1);

namespace Stability\Tests\Feature;

use RuntimeException;

/**
 * A scratch directory for tests that write real files, cleaned up afterwards.
 */
trait TemporaryDirectory
{
    private string $temporaryDirectory;

    private function createTemporaryDirectory(): void
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('stability-test-', true);

        if (!mkdir($path) && !is_dir($path)) {
            throw new RuntimeException("Could not create the temporary directory \"$path\".");
        }

        $this->temporaryDirectory = $path;
    }

    private function removeTemporaryDirectory(): void
    {
        foreach (glob($this->temporaryDirectory . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->temporaryDirectory);
    }
}
