<?php
declare(strict_types=1);

namespace SDL3\Event;

final class MouseButtonDownEvent extends Event
{
    public const TYPE = 0x401;

    public function __construct(
        public readonly int $windowId,
        public readonly int $mouseId,
        public readonly int $button,
        public readonly bool $down,
        public readonly int $clicks,
        public readonly float $x,
        public readonly float $y,
    )
    {
        parent::__construct(self::TYPE);
    }
}
