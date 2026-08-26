<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;
use Stability\Tests\_Fixtures\Config\OutputSettingFactory;

class OutputSettingTest extends TestCase
{
    public function test_when_creating_a_setting_then_clean_path(): void
    {
        $path = "\t/some/path///\n";

        $setting = new OutputSetting(OutputOption::JSON, $path, 'filename');

        $this->assertSame(
            '/some/path' . DIRECTORY_SEPARATOR . 'filename.json',
            $setting->fullFilePath('json'),
        );
    }

    public function test_when_created_setting_has_empty_cleaned_path_then_do_not_add_directory_separator(): void
    {
        $path = " \n\t/// ";

        $setting = new OutputSetting(OutputOption::JSON, $path, 'filename');

        $this->assertSame(
            'filename.json',
            $setting->fullFilePath('json'),
        );
    }

    public function test_create_default_setting(): void
    {
        $setting = OutputSetting::default();

        $this->assertEquals(OutputSettingFactory::default(), $setting);
    }

    public function test_given_a_setting_when_getting_full_path_then_clean_extension(): void
    {
        $setting = new OutputSetting(OutputOption::JSON, '/some/path', 'filename');

        $this->assertSame(
            '/some/path' . DIRECTORY_SEPARATOR . 'filename.xml',
            $setting->fullFilePath('...xml'),
        );
    }

    public function test_given_a_setting_with_empty_filename_when_getting_full_path_then_use_default_filename(): void
    {
        $setting = new OutputSetting(OutputOption::JSON, '/some/path', '');

        $this->assertSame(
            '/some/path' . DIRECTORY_SEPARATOR . 'stability-result.json',
            $setting->fullFilePath('json'),
        );
    }

    public function test_given_a_setting_then_report_its_configured_parts(): void
    {
        $setting = new OutputSetting(OutputOption::JSON, '/some/path/', 'filename');

        $this->assertSame('/some/path', $setting->filePath());
        $this->assertSame('filename', $setting->fileName());
    }

    public function test_given_no_configured_parts_then_report_them_as_empty(): void
    {
        $setting = new OutputSetting(OutputOption::CONSOLE);

        $this->assertSame('', $setting->filePath());
        $this->assertSame('', $setting->fileName());
    }

    public function test_given_a_named_file_then_build_its_path_inside_the_configured_directory(): void
    {
        $setting = new OutputSetting(OutputOption::CONSOLE, '/some/path', 'filename');

        $this->assertSame(
            '/some/path' . DIRECTORY_SEPARATOR . 'stability-graph.mmd',
            $setting->pathFor('stability-graph', 'mmd'),
        );
    }

    public function test_given_no_configured_directory_then_a_named_file_sits_in_the_current_one(): void
    {
        $setting = new OutputSetting(OutputOption::CONSOLE);

        $this->assertSame('stability-chart.svg', $setting->pathFor('stability-chart', '.svg'));
    }

    public function test_given_no_file_name_then_results_fall_back_to_the_default(): void
    {
        $setting = new OutputSetting(OutputOption::JSON);

        $this->assertSame('stability-result.json', $setting->fullFilePath('json'));
    }
}
