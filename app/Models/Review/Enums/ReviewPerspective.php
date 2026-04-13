<?php

namespace App\Models\Review\Enums;

final class ReviewPerspective
{
    public const MANAGER = 'manager';

    public const SELF = 'self';

    public const PEER = 'peer';

    public const UPWARD = 'upward';

    public const ALL = [
        self::MANAGER,
        self::SELF,
        self::PEER,
        self::UPWARD,
    ];
}
