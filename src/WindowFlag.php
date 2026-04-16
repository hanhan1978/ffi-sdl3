<?php
declare(strict_types=1);

namespace SDL3;

enum WindowFlag: int
{
    case Borderless = 0x00000010;
    case AlwaysOnTop = 0x00010000;
    case Transparent = 0x40000000;
}
