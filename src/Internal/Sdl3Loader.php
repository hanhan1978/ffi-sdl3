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
        typedef Uint32 SDL_KeyboardID;
        typedef Uint32 SDL_MouseID;
        typedef Uint32 SDL_MouseButtonFlags;
        typedef Uint32 SDL_Keycode;
        typedef Uint16 SDL_Keymod;
        typedef int    SDL_Scancode;

        typedef struct SDL_KeyboardEvent
        {
            Uint32 type;
            Uint32 reserved;
            Uint64 timestamp;
            Uint32 windowID;
            SDL_KeyboardID which;
            SDL_Scancode scancode;
            SDL_Keycode key;
            SDL_Keymod mod;
            Uint16 raw;
            bool down;
            bool repeat;
        } SDL_KeyboardEvent;

        typedef struct SDL_MouseMotionEvent
        {
            Uint32 type;
            Uint32 reserved;
            Uint64 timestamp;
            Uint32 windowID;
            SDL_MouseID which;
            SDL_MouseButtonFlags state;
            float x;
            float y;
            float xrel;
            float yrel;
        } SDL_MouseMotionEvent;

        typedef struct SDL_MouseButtonEvent
        {
            Uint32 type;
            Uint32 reserved;
            Uint64 timestamp;
            Uint32 windowID;
            SDL_MouseID which;
            Uint8 button;
            bool down;
            Uint8 clicks;
            Uint8 padding;
            float x;
            float y;
        } SDL_MouseButtonEvent;

        typedef struct SDL_QuitEvent
        {
            Uint32 type;
            Uint32 reserved;
            Uint64 timestamp;
        } SDL_QuitEvent;

        typedef union SDL_Event {
            Uint32 type;
            SDL_KeyboardEvent key;
            SDL_MouseMotionEvent motion;
            SDL_MouseButtonEvent button;
            SDL_QuitEvent quit;
            Uint8 padding[128];
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
        bool          SDL_RenderLine(SDL_Renderer* renderer, float x1, float y1, float x2, float y2);
        SDL_Texture*  SDL_CreateTextureFromSurface(SDL_Renderer* renderer, SDL_Surface* surface);
        bool          SDL_SetTextureBlendMode(SDL_Texture* texture, int blendMode);
        bool          SDL_SetTextureAlphaMod(SDL_Texture* texture, Uint8 alpha);
        bool          SDL_GetTextureSize(SDL_Texture* texture, float* w, float* h);
        void          SDL_DestroyTexture(SDL_Texture* texture);
        bool          SDL_RenderTexture(SDL_Renderer* renderer, SDL_Texture* texture, const SDL_FRect* srcrect, const SDL_FRect* dstrect);
        void          SDL_DestroySurface(SDL_Surface* surface);
        Uint32        SDL_GetGlobalMouseState(float* x, float* y);
        bool          SDL_PollEvent(SDL_Event* event);
        Uint64        SDL_GetTicks(void);
        void          SDL_Delay(Uint32 ms);
        SDL_Scancode  SDL_GetScancodeFromKey(SDL_Keycode key, SDL_Keymod* modstate);
        const char*   SDL_GetError(void);
    C;

    public static function load(): FFI
    {
        return FFI::cdef(self::CDEF, LibraryFinder::sdl3Path());
    }
}
