<?php
declare(strict_types=1);

namespace SDL3\Event;

final class MouseButtonUpEvent extends Event
{
    public const TYPE = 0x402;

    public function __construct()
    {
        parent::__construct(self::TYPE);
    }
}
