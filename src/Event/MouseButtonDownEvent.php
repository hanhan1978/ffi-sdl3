<?php
declare(strict_types=1);

namespace SDL3\Event;

final class MouseButtonDownEvent extends Event
{
    public const TYPE = 0x401;

    public function __construct()
    {
        parent::__construct(self::TYPE);
    }
}
