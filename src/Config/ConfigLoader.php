<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Config\Exception\InvalidConfigurationException;

interface ConfigLoader
{
    /**
     * @param string $path The *absolute* path to the configuration file that needs to be loaded.
     *
     * @return Config The loaded configuration.
     *
     * @throws InvalidConfigurationException If the configuration file is missing or invalid for various reasons.
     * When writing a new implementation, make sure each of these cases is covered.
     */
    public function load(string $path): Config;
}
