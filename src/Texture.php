<?php
declare(strict_types=1);

namespace SDL3;

use FFI;
use SDL3\Exception\SdlException;

final class Texture
{
    private ?\FFI\CData $texture;

    public function __construct(
        private SDL $sdl,
        \FFI\CData $texture,
    ) {
        $this->texture = $texture;
    }

    public function setBlendMode(BlendMode $mode): void
    {
        if (!$this->sdl->ffi()->SDL_SetTextureBlendMode($this->texture(), $mode->value)) {
            throw new SdlException('SDL_SetTextureBlendMode failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    public function setAlphaMod(int $alpha): void
    {
        if (!$this->sdl->ffi()->SDL_SetTextureAlphaMod($this->texture(), $alpha)) {
            throw new SdlException('SDL_SetTextureAlphaMod failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function getSize(): array
    {
        $width = $this->sdl->newCData('float');
        $height = $this->sdl->newCData('float');
        if (!$this->sdl->ffi()->SDL_GetTextureSize($this->texture(), FFI::addr($width), FFI::addr($height))) {
            throw new SdlException('SDL_GetTextureSize failed: ' . SDL::errorFrom($this->sdl->ffi()));
        }

        return [(int) $width->cdata, (int) $height->cdata];
    }

    public function destroy(): void
    {
        if ($this->texture === null) {
            return;
        }

        $this->sdl->ffi()->SDL_DestroyTexture($this->texture);
        $this->texture = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }

    /**
     * @internal
     */
    public function cdata(): \FFI\CData
    {
        return $this->texture();
    }

    private function texture(): \FFI\CData
    {
        if ($this->texture === null) {
            throw new SdlException('Texture has already been destroyed.');
        }

        return $this->texture;
    }
}
