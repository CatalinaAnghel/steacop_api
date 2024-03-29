<?php
declare(strict_types=1);

namespace App\Helper;

enum FunctionalityStatusesHelper: string
{
    case Open = 'Open';
    case Closed = 'Done';
    case InProgress = 'In progress';
    case Testing = 'Testing';

    public static function getOrder(): array{
        return [
            self::Open,
            self::InProgress,
            self::Testing,
            self::Closed
        ];
    }
}
