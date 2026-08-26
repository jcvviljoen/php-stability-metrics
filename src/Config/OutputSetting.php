<?php

declare(strict_types=1);

namespace Stability\Config;

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

    /**
     * The configured directory, without a trailing separator. Empty when results are
     * written to the current directory.
     */
    public function filePath(): string
    {
        return rtrim($this->filePath, DIRECTORY_SEPARATOR);
    }

    /**
     * The configured file name, which is empty when the default is to be used.
     */
    public function fileName(): string
    {
        return $this->fileName;
    }

    /**
     * Where the analysis results are written to.
     */
    public function fullFilePath(string $extension): string
    {
        return $this->pathFor(
            empty($this->fileName) ? self::DEFAULT_FILE_NAME : $this->fileName,
            $extension,
        );
    }

    /**
     * Where a file that sits alongside the results is written to, such as a graph or a chart.
     */
    public function pathFor(string $fileName, string $extension): string
    {
        return $this->filePath . $fileName . '.' . ltrim($extension, '.');
    }
}
