<?php
declare(strict_types=1);

namespace SDL3;

use FFI;

final class Color
{
    public function __construct(
        public readonly int $r,
        public readonly int $g,
        public readonly int $b,
        public readonly int $a = 255,
    ) {
    }

    public function toCData(FFI $ffi): \FFI\CData
    {
        $color = $ffi->new('SDL_Color');
        $color->r = $this->r;
        $color->g = $this->g;
        $color->b = $this->b;
        $color->a = $this->a;

        return $color;
    }
}
