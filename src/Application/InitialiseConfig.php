<?php

declare(strict_types=1);

namespace Stability\Application;

use Stability\Application\Exception\UnwritableFileException;

/**
 * Puts a starting configuration file in place for a project that has none yet.
 */
readonly class InitialiseConfig
{
    public function __construct(private string $samplePath)
    {
    }

    /**
     * An existing file is left alone rather than overwritten, so that running this twice
     * cannot cost anyone the configuration they had already written.
     *
     * @throws UnwritableFileException
     */
    public function handle(string $configFile): ConfigInitialisation
    {
        if (is_file($configFile)) {
            return ConfigInitialisation::ALREADY_EXISTED;
        }

        if (!copy($this->samplePath, $configFile)) {
            throw UnwritableFileException::onFailedWrite($configFile);
        }

        return ConfigInitialisation::CREATED;
    }
}
