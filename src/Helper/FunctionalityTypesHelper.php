<?php
declare(strict_types=1);

namespace App\Helper;

enum FunctionalityTypesHelper: string
{
    case Epic = 'Epic';
    case Story = 'Story';
    case Task = 'Task';
    case Subtask = 'Subtask';
    case Bug = 'Bug';

    /**
     * @return string[]
     */
    public function getPossibleChildIssues(): array{
        return match ($this) {
            self::Epic               => [
                self::Story->value,
                self::Task->value,
                self::Bug->value
            ],
            self::Story, self::Task, self::Bug  => [
                self::Subtask->value
            ],
            self::Subtask => []
        };
    }
}
