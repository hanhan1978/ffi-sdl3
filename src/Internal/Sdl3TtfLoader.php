<?php
declare(strict_types=1);

namespace SDL3\Internal;

use FFI;

final class Sdl3TtfLoader
{
    private const CDEF = <<<'C'
        typedef unsigned char      Uint8;
        typedef unsigned long      size_t;

        typedef struct SDL_Color {
            Uint8 r, g, b, a;
        } SDL_Color;

        typedef struct SDL_Surface SDL_Surface;
        typedef struct TTF_Font    TTF_Font;

        bool          TTF_Init(void);
        void          TTF_Quit(void);
        TTF_Font*     TTF_OpenFont(const char* path, float ptsize);
        void          TTF_CloseFont(TTF_Font* font);
        SDL_Surface*  TTF_RenderText_Blended(TTF_Font* font, const char* text, size_t length, SDL_Color fg);
    C;

    public static function load(): FFI
    {
        return FFI::cdef(self::CDEF, LibraryFinder::sdl3TtfPath());
    }
}
