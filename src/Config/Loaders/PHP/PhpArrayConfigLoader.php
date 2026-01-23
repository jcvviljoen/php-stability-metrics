<?php

declare(strict_types=1);

namespace Stability\Config\Loaders\PHP;

use Override;
use Stability\Config\Config;
use Stability\Config\ConfigLoader;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\LoadedConfig;
use Stability\Config\Loaders\LoadedModule;
use Stability\Config\ModuleList;
use Stability\Output\OutputOption;
use Stability\Output\OutputSetting;

// phpcs:disable SlevomatCodingStandard.Commenting.DocCommentSpacing.IncorrectLinesCountAfterLastContent
/**
 * @phpstan-type RawConfig array{
 *     modules: array<
 *         string,
 *         array{
 *             module: string,
 *             threshold_zone_of_pain: float,
 *             threshold_zone_of_uselessness:float,
 *             exclude: array<string>
 *         }
 *     >|null,
 *     output: array{
 *         option: string|null,
 *         fileName: string|null,
 *         filePath: string|null
 *     }|null
 * }
 *
 * You can find a sample of the expected configuration file structure at the root of
 * the project in the file `stability.php.sample`.
 */
readonly class PhpArrayConfigLoader implements ConfigLoader
{
    #[Override] public function load(string $path): Config
    {
        if (!file_exists($path)) {
            throw InvalidConfigurationException::onMissingConfigFile($path);
        }

        /** @var RawConfig $config */
        $config = include $path;

        /** @var array<int, array<string, mixed>> $modules */
        $modules = $config['modules'] ?? [];

        /** @var list<LoadedModule> $modules */
        $modules = array_map(
            function (array $moduleConfig) {
                /** @var string $moduleName */
                $moduleName = $moduleConfig['name'] ?? throw InvalidConfigurationException::onMissingModuleName();
                /** @var string $modulePath */
                $modulePath = $moduleConfig['path'] ?? throw InvalidConfigurationException::onMissingModulePath();
                /** @var float $thresholdZoneOfPain */
                $thresholdZoneOfPain = $moduleConfig['threshold_zone_of_pain'] ?? 0.7;
                /** @var float $thresholdZoneOfUselessness */
                $thresholdZoneOfUselessness = $moduleConfig['threshold_zone_of_uselessness'] ?? 0.7;
                /** @var list<string> $exclude */
                $exclude = $moduleConfig['exclude'] ?? [];

                return new LoadedModule(
                    $moduleName,
                    $modulePath,
                    $thresholdZoneOfPain,
                    $thresholdZoneOfUselessness,
                    $exclude,
                );
            },
            $modules,
        );

        isset($config['output'])
            ? $outputSettings = new OutputSetting(
                OutputOption::from(
                    $config['output']['option'] ?? throw InvalidConfigurationException::onMissingOutputOption(),
                ),
                $config['output']['fileName'] ?? '',
                $config['output']['filePath'] ?? '',
            ) : $outputSettings = OutputSetting::default();

        return new LoadedConfig(
            new ModuleList($modules),
            $outputSettings,
        );
    }
}
