<?php

namespace Instability;

use Instability\Config\Module;

interface FileReader
{
    public function files(Module $module): array;
}
