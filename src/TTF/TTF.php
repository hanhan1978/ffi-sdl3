<?php
declare(strict_types=1);

namespace SDL3\TTF;

use FFI;
use SDL3\Exception\TtfException;
use SDL3\SDL;
use SDL3\Internal\Sdl3TtfLoader;

final class TTF
{
    private bool $quit = false;

    private function __construct(
        private SDL $sdl,
        private FFI $ffi,
    ) {
    }

    public static function init(SDL $sdl): self
    {
        $ffi = Sdl3TtfLoader::load();

        if (!$ffi->TTF_Init()) {
            throw new TtfException('TTF_Init failed: ' . SDL::errorFrom($sdl->ffi()));
        }

        return new self($sdl, $ffi);
    }

    public function ffi(): FFI
    {
        return $this->ffi;
    }

    public function sdl(): SDL
    {
        return $this->sdl;
    }

    public function quit(): void
    {
        if ($this->quit) {
            return;
        }

        $this->ffi->TTF_Quit();
        $this->quit = true;
    }

    public function __destruct()
    {
        $this->quit();
    }
}
