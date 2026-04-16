<?php
declare(strict_types=1);

namespace SDL3\Event;

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
            MouseButtonDownEvent::TYPE => new MouseButtonDownEvent(),
            MouseButtonUpEvent::TYPE => new MouseButtonUpEvent(),
            default => null,
        };
    }
}
