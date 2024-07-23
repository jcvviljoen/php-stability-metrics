<?php /** @noinspection PhpSameParameterValueInspection */

namespace Instability;

class Counter
{
    private function __construct(
        private int $abstractClassCount,
        private int $interfaceCount,
        private int $totalClasses,
    ) {
    }

    public function addAbstractClass(): void
    {
        $this->abstractClassCount++;
    }

    public function getAbstractClassCount(): int
    {
        return $this->abstractClassCount;
    }

    public function addInterface(): void
    {
        $this->interfaceCount++;
    }

    public function getInterfaceCount(): int
    {
        return $this->interfaceCount;
    }

    public function addClass(): void
    {
        $this->totalClasses++;
    }

    public function getClassCount(): int
    {
        return $this->totalClasses;
    }

    public static function empty(): self
    {
        return new self(0, 0, 0);
    }
}
