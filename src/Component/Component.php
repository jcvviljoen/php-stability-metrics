<?php /** @noinspection PhpSameParameterValueInspection */

namespace Stability\Component;

readonly class Component
{
    /**
     * @param array<FileData> $fileData
     */
    public function __construct(
        public string $name,
        public string $partialNamespace, // TODO maybe `$mainNamespace`?
        public array $fileData,
        public ClassCounter $counter,
    ) {
    }

    public function countUsagesOf(Component $other): int
    {
        $count = 0;

        foreach ($this->fileData as $fileData) {
            foreach ($fileData->imports as $import) {
                if (str_contains($import, $other->partialNamespace)) {
                    $count++;
                    break;
                }
            }
        }

        return $count;
    }
}
