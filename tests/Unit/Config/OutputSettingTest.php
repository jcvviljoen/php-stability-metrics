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
}
