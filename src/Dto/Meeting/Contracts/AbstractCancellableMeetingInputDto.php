<?php
declare(strict_types=1);

namespace App\Dto\Meeting\Contracts;

use App\Dto\Traits\IsCanceledTrait;

abstract class AbstractCancellableMeetingInputDto extends AbstractMeetingInputDto
{
    use IsCanceledTrait;
}
