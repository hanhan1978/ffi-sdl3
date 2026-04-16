# hanhan1978/ffi-sdl3

Small PHP FFI bindings for SDL3 and SDL3_ttf.

This package was split out from the overlay demo so it can be reused as an
independent Composer library.

## Requirements

- PHP 8.4 or later
- `ext-ffi`
- SDL3
- SDL3_ttf
- Apache-2.0 license

## Installation

```bash
composer require hanhan1978/ffi-sdl3:^0.1
```

## Local development

If you are developing against a local checkout, add a path repository to the
parent project's `composer.json` and run `composer install`.

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../ffi-sdl3"
    }
  ]
}
```

## Usage

```php
use SDL3\BlendMode;
use SDL3\Color;
use SDL3\SDL;
use SDL3\TTF\Font;
use SDL3\TTF\TTF;
use SDL3\Window;
use SDL3\WindowFlag;

$sdl = SDL::init();
$ttf = TTF::init($sdl);
$font = new Font($ttf, '/System/Library/Fonts/Helvetica.ttc', 32.0);

$window = new Window($sdl, 'Overlay', 640, 80, [
    WindowFlag::Borderless,
    WindowFlag::AlwaysOnTop,
    WindowFlag::Transparent,
]);

$renderer = $window->createRenderer();
$renderer->setDrawBlendMode(BlendMode::Blend);

$surface = $font->renderTextBlended('Hello', new Color(255, 230, 80));
$texture = $renderer->createTextureFromSurface($surface);
$surface->destroy();
```

## Library lookup

`LibraryFinder` searches for SDL3 and SDL3_ttf in this order:

- `SDL3_LIBRARY_PATH`
- `SDL3_TTF_LIBRARY_PATH`
- `pkg-config`
- Common Homebrew paths

## Public API

- `SDL3\SDL`
- `SDL3\Window`
- `SDL3\Renderer`
- `SDL3\Texture`
- `SDL3\Surface`
- `SDL3\Rect`
- `SDL3\Color`
- `SDL3\WindowFlag`
- `SDL3\BlendMode`
- `SDL3\Event\*`
- `SDL3\TTF\TTF`
- `SDL3\TTF\Font`

## License

Apache-2.0
