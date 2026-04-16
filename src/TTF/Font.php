<?php
declare(strict_types=1);

namespace SDL3\TTF;

use FFI;
use SDL3\Exception\TtfException;
use SDL3\Color;
use SDL3\Surface;
use SDL3\SDL;

final class Font
{
    private ?\FFI\CData $font;

    public function __construct(
        private TTF $ttf,
        string $path,
        float $pointSize,
    ) {
        $font = $this->ttf->ffi()->TTF_OpenFont($path, $pointSize);
        if (FFI::isNull($font)) {
            throw new TtfException('TTF_OpenFont failed: ' . self::errorString($this->ttf));
        }

        $this->font = $font;
    }

    public function renderTextBlended(string $text, Color $color): Surface
    {
        $colorCData = $color->toCData($this->ttf->ffi());
        $surface = $this->ttf->ffi()->TTF_RenderText_Blended($this->font(), $text, 0, $colorCData);
        if (FFI::isNull($surface)) {
            throw new TtfException('TTF_RenderText_Blended failed: ' . self::errorString($this->ttf));
        }

        return new Surface($this->ttf->sdl(), $surface);
    }

    public function close(): void
    {
        if ($this->font === null) {
            return;
        }

        $this->ttf->ffi()->TTF_CloseFont($this->font);
        $this->font = null;
    }

    public function __destruct()
    {
        $this->close();
    }

    private function font(): \FFI\CData
    {
        if ($this->font === null) {
            throw new TtfException('Font has already been closed.');
        }

        return $this->font;
    }

    private static function errorString(TTF $ttf): string
    {
        return SDL::errorFrom($ttf->sdl()->ffi());
    }
}
