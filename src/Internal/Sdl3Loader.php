<?php
declare(strict_types=1);

namespace SDL3\Internal;

use FFI;

final class Sdl3Loader
{
    private const CDEF = <<<'C'
        typedef unsigned char       Uint8;
        typedef unsigned int        Uint32;
        typedef unsigned long long  Uint64;

        typedef struct SDL_Color {
            Uint8 r, g, b, a;
        } SDL_Color;

        typedef struct SDL_FRect {
            float x, y, w, h;
        } SDL_FRect;

        typedef struct SDL_Window SDL_Window;
        typedef struct SDL_Renderer SDL_Renderer;
        typedef struct SDL_Texture SDL_Texture;
        typedef struct SDL_Surface SDL_Surface;
        typedef union SDL_Event {
            Uint32 type;
            char   padding[128];
        } SDL_Event;

        typedef Uint32 SDL_InitFlags;
        typedef Uint64 SDL_WindowFlags;

        bool          SDL_Init(SDL_InitFlags flags);
        void          SDL_Quit(void);
        SDL_Window*   SDL_CreateWindow(const char* title, int w, int h, SDL_WindowFlags flags);
        void          SDL_DestroyWindow(SDL_Window* window);
        bool          SDL_SetWindowAlwaysOnTop(SDL_Window* window, bool on_top);
        bool          SDL_SetWindowPosition(SDL_Window* window, int x, int y);
        bool          SDL_SetWindowSize(SDL_Window* window, int w, int h);
        bool          SDL_GetWindowPosition(SDL_Window* window, int* x, int* y);
        bool          SDL_GetWindowSizeInPixels(SDL_Window* window, int* w, int* h);
        SDL_Renderer* SDL_CreateRenderer(SDL_Window* window, const char* name);
        void          SDL_DestroyRenderer(SDL_Renderer* renderer);
        bool          SDL_SetRenderDrawColor(SDL_Renderer* renderer, Uint8 r, Uint8 g, Uint8 b, Uint8 a);
        bool          SDL_RenderClear(SDL_Renderer* renderer);
        bool          SDL_RenderPresent(SDL_Renderer* renderer);
        bool          SDL_SetRenderDrawBlendMode(SDL_Renderer* renderer, int blendMode);
        bool          SDL_RenderFillRect(SDL_Renderer* renderer, const SDL_FRect* rect);
        SDL_Texture*  SDL_CreateTextureFromSurface(SDL_Renderer* renderer, SDL_Surface* surface);
        bool          SDL_SetTextureBlendMode(SDL_Texture* texture, int blendMode);
        bool          SDL_SetTextureAlphaMod(SDL_Texture* texture, Uint8 alpha);
        bool          SDL_GetTextureSize(SDL_Texture* texture, float* w, float* h);
        void          SDL_DestroyTexture(SDL_Texture* texture);
        bool          SDL_RenderTexture(SDL_Renderer* renderer, SDL_Texture* texture, const SDL_FRect* srcrect, const SDL_FRect* dstrect);
        void          SDL_DestroySurface(SDL_Surface* surface);
        Uint32        SDL_GetGlobalMouseState(float* x, float* y);
        bool          SDL_PollEvent(SDL_Event* event);
        void          SDL_Delay(Uint32 ms);
        const char*   SDL_GetError(void);
    C;

    public static function load(): FFI
    {
        return FFI::cdef(self::CDEF, LibraryFinder::sdl3Path());
    }
}
