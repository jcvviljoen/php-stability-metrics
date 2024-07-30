<?php

namespace Instability\Component;

enum FileType
{
    case ABSTRACT_CLASS;
    case T_CLASS;
    case INTERFACE;
    case UNKNOWN;
}
