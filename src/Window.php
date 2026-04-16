<?php
declare(strict_types=1);

namespace SDL3;

use FFI;
use SDL3\Exception\SdlException;

final class Window
{
    private ?\FFI\CData $window;

    /**
     * @param list<WindowFlag> $flags
     */
    public function __construct(
        private SDL $sdl,
        string $title,
        int $width,
        int $height,
        array $flags = [],
    ) {
        $sdlFlags = 0;
        foreach ($flags as $flag) {
            $sdlFlags |= $flag->value;
        }

        $window = $this->sdl->ffi()->SDL_CreateWindow($title, $width, $height, $sdlFlags);
        if (FFI::isNull($window)) {
            throw new SdlException('SDL_CreateWindow failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        $this->window = $window;
    }

    public function setAlwaysOnTop(bool $enabled): void
    {
        $window = $this->window();
        if (!$this->sdl->ffi()->SDL_SetWindowAlwaysOnTop($window, $enabled)) {
            throw new SdlException('SDL_SetWindowAlwaysOnTop failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function setPosition(int $x, int $y): void
    {
        $window = $this->window();
        if (!$this->sdl->ffi()->SDL_SetWindowPosition($window, $x, $y)) {
            throw new SdlException('SDL_SetWindowPosition failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function setSize(int $width, int $height): void
    {
        $window = $this->window();
        if (!$this->sdl->ffi()->SDL_SetWindowSize($window, $width, $height)) {
            throw new SdlException('SDL_SetWindowSize failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function getPosition(): array
    {
        $window = $this->window();
        $x = $this->sdl->newCData('int');
        $y = $this->sdl->newCData('int');

        if (!$this->sdl->ffi()->SDL_GetWindowPosition($window, FFI::addr($x), FFI::addr($y))) {
            throw new SdlException('SDL_GetWindowPosition failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        return [(int) $x->cdata, (int) $y->cdata];
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function getSizeInPixels(): array
    {
        $window = $this->window();
        $width = $this->sdl->newCData('int');
        $height = $this->sdl->newCData('int');

        if (!$this->sdl->ffi()->SDL_GetWindowSizeInPixels($window, FFI::addr($width), FFI::addr($height))) {
            throw new SdlException('SDL_GetWindowSizeInPixels failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        return [(int) $width->cdata, (int) $height->cdata];
    }

    public function createRenderer(?string $name = null): Renderer
    {
        $window = $this->window();
        $renderer = $this->sdl->ffi()->SDL_CreateRenderer($window, $name);
        if (FFI::isNull($renderer)) {
            throw new SdlException('SDL_CreateRenderer failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        return new Renderer($this->sdl, $renderer);
    }

    public function destroy(): void
    {
        if ($this->window === null) {
            return;
        }

        $this->sdl->ffi()->SDL_DestroyWindow($this->window);
        $this->window = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }

    private function window(): \FFI\CData
    {
        if ($this->window === null) {
            throw new SdlException('Window has already been destroyed.');
        }

        return $this->window;
    }
}
