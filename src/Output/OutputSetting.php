<?php

declare(strict_types=1);

namespace Stability\Output;

readonly class OutputSetting
{
    private const string DEFAULT_FILE_NAME = 'stability-result';

    private string $path;

    public function __construct(
        public OutputOption $option,
        string $path,
        private string $fileName,
    ) {
        // Trim and remove trailing slashes
        $cleanPath = rtrim(trim($path), DIRECTORY_SEPARATOR);

        empty($cleanPath)
            ? $this->path = $cleanPath
            : $this->path = $cleanPath . DIRECTORY_SEPARATOR;
    }

    public static function default(): self
    {
        return new self(
            OutputOption::JSON,
            '',
            self::DEFAULT_FILE_NAME,
        );
    }

    public function fullFilePath(string $extension): string
    {
        $cleanExtension = ltrim($extension, '.');

        return $this->path
            . (empty($this->fileName) ? self::DEFAULT_FILE_NAME : $this->fileName)
            . '.'
            . $cleanExtension;
    }
}
