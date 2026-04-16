# hanhan1978/ffi-sdl3

PHP FFI で SDL3 と SDL3_ttf を扱うための小さなラッパーです。
`overlay.php` から FFI 直叩きを消すために切り出した、独立した Composer パッケージです。

## 対象

- PHP 8.4 以上
- `ext-ffi`
- SDL3
- SDL3_ttf
- ライセンスは Apache-2.0

## 使い方

ローカル開発では、チェックアウト先の親プロジェクトの `composer.json` に path repository を追加してから `composer install` します。

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

利用例:

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

## ライブラリ探索

`LibraryFinder` は次の順で SDL3 / SDL3_ttf を探します。

- `SDL3_LIBRARY_PATH`
- `SDL3_TTF_LIBRARY_PATH`
- `pkg-config`
- Homebrew の代表的なパス

## 公開 API

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

## ライセンス

Apache-2.0
