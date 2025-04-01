<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Exception\InvalidConfigurationException;

/**
 * @phpstan-type RawConfig array{
 *     base_path: string|null,
 *     modules: array<
 *      int,
 *      array{
 *       module: string,
 *       threshold_zone_of_pain: float,
 *       threshold_zone_of_uselessness:float,
 *       exclude: array<string>
 *      }
 *     >|null
 * }
 */
interface ConfigLoader
{
    /**
     * @param string $path The path to the configuration file that needs to be loaded.
     *
     * @return Config The loaded configuration.
     * You can find a sample of the expected configuration file structure at the root of
     * the project in the file `stability.php.sample`.
     *
     * @throws InvalidConfigurationException If the configuration file is missing or invalid for various reasons.
     * There are various static methods on the `InvalidConfigurationException` class that can be used to indicate
     * what the problem is. When writing a new implementation, make sure each of these cases is covered.
     */
    public function load(string $path): Config;
}
