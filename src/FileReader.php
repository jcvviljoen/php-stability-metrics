<?php

namespace Stability;

use Stability\Config\Module;

interface FileReader
{
    /**
     * @return array<string>
     */
    public function files(Module $module): array;
}
