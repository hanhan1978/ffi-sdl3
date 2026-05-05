<?php
declare(strict_types=1);

namespace SDL3\Event;

final class KeyUpEvent extends Event
{
    public const TYPE = 0x301;

    public function __construct(
        public readonly int $scancode,
        public readonly int $keycode,
        public readonly int $modifiers,
        public readonly bool $down,
        public readonly bool $repeat,
    ) {
        parent::__construct(self::TYPE);
    }
}
