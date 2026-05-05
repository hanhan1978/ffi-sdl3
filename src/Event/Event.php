<?php
declare(strict_types=1);

namespace SDL3\Event;

use FFI;

abstract class Event
{
    public function __construct(
        public readonly int $type,
    ) {
    }

    public static function fromType(int $type): ?self
    {
        return match ($type) {
            QuitEvent::TYPE => new QuitEvent(),
            KeyDownEvent::TYPE => new KeyDownEvent(0, 0, 0, false, false),
            KeyUpEvent::TYPE => new KeyUpEvent(0, 0, 0, false, false),
            MouseMotionEvent::TYPE => new MouseMotionEvent(0, 0, 0, 0.0, 0.0, 0.0, 0.0),
            MouseButtonDownEvent::TYPE => new MouseButtonDownEvent(0, 0, 0, false, 0, 0.0, 0.0),
            MouseButtonUpEvent::TYPE => new MouseButtonUpEvent(0, 0, 0, false, 0, 0.0, 0.0),
            default => null,
        };
    }

    public static function fromCData(\FFI\CData $event): ?self
    {
        $type = (int) $event->type;

        return match ($type) {
            QuitEvent::TYPE => new QuitEvent(),
            KeyDownEvent::TYPE => new KeyDownEvent(
                (int) $event->key->scancode,
                (int) $event->key->key,
                (int) $event->key->mod,
                (bool) $event->key->down,
                (bool) $event->key->repeat,
            ),
            KeyUpEvent::TYPE => new KeyUpEvent(
                (int) $event->key->scancode,
                (int) $event->key->key,
                (int) $event->key->mod,
                (bool) $event->key->down,
                (bool) $event->key->repeat,
            ),
            MouseMotionEvent::TYPE => new MouseMotionEvent(
                (int) $event->motion->windowID,
                (int) $event->motion->which,
                (int) $event->motion->state,
                (float) $event->motion->x,
                (float) $event->motion->y,
                (float) $event->motion->xrel,
                (float) $event->motion->yrel,
            ),
            MouseButtonDownEvent::TYPE => new MouseButtonDownEvent(
                (int) $event->button->windowID,
                (int) $event->button->which,
                (int) $event->button->button,
                (bool) $event->button->down,
                (int) $event->button->clicks,
                (float) $event->button->x,
                (float) $event->button->y,
            ),
            MouseButtonUpEvent::TYPE => new MouseButtonUpEvent(
                (int) $event->button->windowID,
                (int) $event->button->which,
                (int) $event->button->button,
                (bool) $event->button->down,
                (int) $event->button->clicks,
                (float) $event->button->x,
                (float) $event->button->y,
            ),
            default => null,
        };
    }
}
