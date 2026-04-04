<?php

namespace App\Models\Skill\Enums;

final class SkillCategory
{
    public const TECHNICAL = 'technical';

    public const SOFT = 'soft';

    public const LANGUAGE = 'language';

    public const ALL = [
        self::TECHNICAL,
        self::SOFT,
        self::LANGUAGE,
    ];
}
