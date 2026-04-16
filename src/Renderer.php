<?php
declare(strict_types=1);

namespace SDL3;

use FFI;
use SDL3\Exception\SdlException;

final class Renderer
{
    private ?\FFI\CData $renderer;

    public function __construct(
        private SDL $sdl,
        \FFI\CData $renderer,
    ) {
        $this->renderer = $renderer;
    }

    public function setDrawBlendMode(BlendMode $mode): void
    {
        if (!$this->sdl->ffi()->SDL_SetRenderDrawBlendMode($this->renderer(), $mode->value)) {
            throw new SdlException('SDL_SetRenderDrawBlendMode failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function setDrawColor(int $r, int $g, int $b, int $a): void
    {
        if (!$this->sdl->ffi()->SDL_SetRenderDrawColor($this->renderer(), $r, $g, $b, $a)) {
            throw new SdlException('SDL_SetRenderDrawColor failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function clear(): void
    {
        if (!$this->sdl->ffi()->SDL_RenderClear($this->renderer())) {
            throw new SdlException('SDL_RenderClear failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function fillRect(?Rect $rect): void
    {
        if ($rect === null) {
            if (!$this->sdl->ffi()->SDL_RenderFillRect($this->renderer(), null)) {
                throw new SdlException('SDL_RenderFillRect failed: ' . SDL::errorFrom($this->sdl->ffi()));
            }

            return;
        }

        $rectCData = $rect->toCData($this->sdl->ffi());
        if (!$this->sdl->ffi()->SDL_RenderFillRect($this->renderer(), FFI::addr($rectCData))) {
            throw new SdlException('SDL_RenderFillRect failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function renderTexture(Texture $texture, ?Rect $src, Rect $dst): void
    {
        $srcPtr = null;
        if ($src !== null) {
            $srcCData = $src->toCData($this->sdl->ffi());
            $srcPtr = FFI::addr($srcCData);
        }

        $dstCData = $dst->toCData($this->sdl->ffi());
        if (!$this->sdl->ffi()->SDL_RenderTexture($this->renderer(), $texture->cdata(), $srcPtr, FFI::addr($dstCData))) {
            throw new SdlException('SDL_RenderTexture failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function createTextureFromSurface(Surface $surface): Texture
    {
        $surfaceCData = $this->sdl->castCData('SDL_Surface *', $surface->cdata());
        $texture = $this->sdl->ffi()->SDL_CreateTextureFromSurface($this->renderer(), $surfaceCData);
        if (FFI::isNull($texture)) {
            throw new SdlException('SDL_CreateTextureFromSurface failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        return new Texture($this->sdl, $texture);
    }

    public function present(): void
    {
        if (!$this->sdl->ffi()->SDL_RenderPresent($this->renderer())) {
            throw new SdlException('SDL_RenderPresent failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function destroy(): void
    {
        if ($this->renderer === null) {
            return;
        }

        $this->sdl->ffi()->SDL_DestroyRenderer($this->renderer);
        $this->renderer = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }

    private function renderer(): \FFI\CData
    {
        if ($this->renderer === null) {
            throw new SdlException('Renderer has already been destroyed.');
        }

        return $this->renderer;
    }
}
