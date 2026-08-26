<?php

declare(strict_types=1);

namespace Stability\Application;

use Stability\Metric\Result;

/**
 * Everything an analysis produced, so that whoever asked for it can report on it.
 *
 * The results themselves have already been written by the configured output writer by
 * the time this is returned. What is left here is what the caller may want to say about
 * the run, which for a CLI means the files it wrote and the cycles it found.
 */
readonly class AnalysisReport
{
    /**
     * @param Result $result The metrics calculated for every analysed component.
     * @param list<list<string>> $cycles Each inner list names the components of one circular dependency.
     * @param string|null $graphFile Where the dependency graph was written, when one was asked for.
     * @param string|null $chartFile Where the stability chart was written, when one was asked for.
     */
    public function __construct(
        public Result $result,
        public array $cycles,
        public ?string $graphFile = null,
        public ?string $chartFile = null,
    ) {
    }

    public function hasCycles(): bool
    {
        return !empty($this->cycles);
    }
}
