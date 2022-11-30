<?php

namespace App\Enum;

enum SortDirection: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public function next(string $direction): string
    {
        if ($direction == null || $direction == self::DESC)
            return self::ASC->value;
        return self::DESC->value;
    }

    public function current(string $direction): string
    {
        return self::tryFrom($direction)->value;
    }

}
