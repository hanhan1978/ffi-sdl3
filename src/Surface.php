<?php
declare(strict_types=1);

namespace SDL3;

use FFI;
use SDL3\Exception\SdlException;

final class Surface
{
    private ?\FFI\CData $surface;

    public function __construct(
        private SDL $sdl,
        \FFI\CData $surface,
    ) {
        $this->surface = $surface;
    }

    /**
     * @internal
     */
    public function cdata(): \FFI\CData
    {
        return $this->surface();
    }

    public function destroy(): void
    {
        if ($this->surface === null) {
            return;
        }

        $surface = $this->sdl->castCData('SDL_Surface *', $this->surface);
        $this->sdl->ffi()->SDL_DestroySurface($surface);
        $this->surface = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }

    private function surface(): \FFI\CData
    {
        if ($this->surface === null) {
            throw new SdlException('Surface has already been destroyed.');
        }

        return $this->surface;
    }
}
