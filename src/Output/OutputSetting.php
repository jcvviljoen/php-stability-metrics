<?php

declare(strict_types=1);

namespace Stability\Output;

readonly class OutputSetting
{
    private const string DEFAULT_FILE_NAME = 'stability-result';

    private string $filePath;

    public function __construct(
        public OutputOption $option,
        string $filePath = '',
        private string $fileName = '',
    ) {
        // Trim and remove trailing slashes
        $cleanPath = rtrim(trim($filePath), DIRECTORY_SEPARATOR);

        empty($cleanPath)
            ? $this->filePath = $cleanPath
            : $this->filePath = $cleanPath . DIRECTORY_SEPARATOR;
    }

    public static function default(): self
    {
        return new self(
            OutputOption::CONSOLE,
            '',
            self::DEFAULT_FILE_NAME,
        );
    }

    public function fullFilePath(string $extension): string
    {
        $cleanExtension = ltrim($extension, '.');

        return $this->filePath
            . (empty($this->fileName) ? self::DEFAULT_FILE_NAME : $this->fileName)
            . '.'
            . $cleanExtension;
    }
}
