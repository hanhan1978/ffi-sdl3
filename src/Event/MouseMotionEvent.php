<?php
declare(strict_types=1);

namespace SDL3\Event;

final class MouseMotionEvent extends Event
{
    public const TYPE = 0x400;

    public function __construct(
        public readonly int $windowId,
        public readonly int $mouseId,
        public readonly int $buttonState,
        public readonly float $x,
        public readonly float $y,
        public readonly float $xrel,
        public readonly float $yrel,
    ) {
        parent::__construct(self::TYPE);
    }
}
