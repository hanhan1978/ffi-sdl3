<?php
declare(strict_types=1);

namespace SDL3;

use FFI;

final class Rect
{
    public function __construct(
        public float $x,
        public float $y,
        public float $w,
        public float $h,
    ) {
    }

    public function toCData(FFI $ffi): \FFI\CData
    {
        $rect = $ffi->new('SDL_FRect');
        $rect->x = $this->x;
        $rect->y = $this->y;
        $rect->w = $this->w;
        $rect->h = $this->h;

        return $rect;
    }
}
