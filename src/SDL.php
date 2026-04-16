<?php
declare(strict_types=1);

namespace SDL3;

use FFI;
use Generator;
use SDL3\Exception\SdlException;
use SDL3\Event\Event;
use SDL3\Internal\Sdl3Loader;

final class SDL
{
    public const INIT_VIDEO = 0x00000020;

    private bool $quit = false;

    private function __construct(
        private FFI $ffi,
    ) {
    }

    public static function init(int $flags = self::INIT_VIDEO): self
    {
        $ffi = Sdl3Loader::load();

        if (!$ffi->SDL_Init($flags)) {
            throw new SdlException('SDL_Init failed: ' . self::errorFrom($ffi));
        }

        return new self($ffi);
    }

    /**
     * @internal
     */
    public function ffi(): FFI
    {
        return $this->ffi;
    }

    /**
     * @internal
     */
    public function newCData(string $type): \FFI\CData
    {
        return $this->ffi->new($type);
    }

    /**
     * @internal
     */
    public function castCData(string $type, \FFI\CData $value): \FFI\CData
    {
        return $this->ffi->cast($this->ffi->type($type), FFI::cast('void *', $value));
    }

    public function delay(int $ms): void
    {
        $this->ffi->SDL_Delay($ms);
    }

    /**
     * @return array{0: int, 1: float, 2: float}
     */
    public function getGlobalMouseState(): array
    {
        $x = $this->newCData('float');
        $y = $this->newCData('float');
        $buttons = $this->ffi->SDL_GetGlobalMouseState(FFI::addr($x), FFI::addr($y));

        return [(int) $buttons, (float) $x->cdata, (float) $y->cdata];
    }

    /**
     * @return Generator<int, Event>
     */
    public function pollEvents(): Generator
    {
        $event = $this->newCData('SDL_Event');

        while ($this->ffi->SDL_PollEvent(FFI::addr($event))) {
            $parsed = Event::fromType((int) $event->type);
            if ($parsed !== null) {
                yield $parsed;
            }
        }
    }

    public function quit(): void
    {
        if ($this->quit) {
            return;
        }

        $this->ffi->SDL_Quit();
        $this->quit = true;
    }

    public function __destruct()
    {
        $this->quit();
    }

    public static function errorFrom(FFI $ffi): string
    {
        return (string) $ffi->SDL_GetError();
    }
}
