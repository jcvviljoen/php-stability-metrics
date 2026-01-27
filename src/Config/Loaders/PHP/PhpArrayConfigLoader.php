<?php

declare(strict_types=1);

namespace Stability\Config\Loaders\PHP;

use Override;
use Stability\Config\ConfigLoader;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\StabilityConfig;
use Stability\Config\Loaders\StabilityModule;
use Stability\Config\Loaders\StabilityModuleList;
use Stability\Output\OutputOption;
use Stability\Output\OutputSetting;

// phpcs:disable SlevomatCodingStandard.Commenting.DocCommentSpacing.IncorrectLinesCountAfterLastContent
/**
 * @phpstan-type RawConfig array{
 *     modules: array<
 *         string,
 *         array{
 *             module: string,
 *             thresholdZoneOfPain: float,
 *             thresholdZoneOfUselessness:float,
 *             exclude: array<string>
 *         }
 *     >|null,
 *     outputSettings: array{
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
    #[Override] public function load(string $path): StabilityConfig
    {
        if (!file_exists($path)) {
            throw InvalidConfigurationException::onMissingConfigFile($path);
        }

        /** @var RawConfig $config */
        $config = include $path;

        /** @var array<int, array<string, mixed>> $modules */
        $modules = $config['modules'] ?? [];

        /** @var list<StabilityModule> $modules */
        $modules = array_map(
            function (array $moduleConfig) {
                /** @var string $moduleName */
                $moduleName = $moduleConfig['name'] ?? throw InvalidConfigurationException::onMissingModuleName();
                /** @var string $modulePath */
                $modulePath = $moduleConfig['path'] ?? throw InvalidConfigurationException::onMissingModulePath();
                /** @var float $thresholdZoneOfPain */
                $thresholdZoneOfPain = $moduleConfig['thresholdZoneOfPain'] ?? 0.7;
                /** @var float $thresholdZoneOfUselessness */
                $thresholdZoneOfUselessness = $moduleConfig['thresholdZoneOfUselessness'] ?? 0.7;
                /** @var list<string> $exclude */
                $exclude = $moduleConfig['exclude'] ?? [];

                return new StabilityModule(
                    $moduleName,
                    $modulePath,
                    $thresholdZoneOfPain,
                    $thresholdZoneOfUselessness,
                    $exclude,
                );
            },
            $modules,
        );

        $outputSettings = isset($config['outputSettings'])
            ? new OutputSetting(
                OutputOption::from(
                    $config['outputSettings']['option'] ?? throw InvalidConfigurationException::onMissingOutputOption(),
                ),
                $config['outputSettings']['fileName'] ?? '',
                $config['outputSettings']['filePath'] ?? '',
            )
            : null;

        return new StabilityConfig(
            new StabilityModuleList($modules),
            $outputSettings,
        );
    }
}
