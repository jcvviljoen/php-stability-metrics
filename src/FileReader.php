<?php

namespace Stability;

use Stability\Config\Module;

interface FileReader
{
    public function files(Module $module): array;
}
