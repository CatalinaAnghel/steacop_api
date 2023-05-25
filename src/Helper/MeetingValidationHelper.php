<?php
declare(strict_types=1);

namespace App\Helper;

enum MeetingValidationHelper: string
{
    case CanceledMeetingErrorMessage = 'The meeting is canceled, it cannot be updated';
    case CompletedMeetingErrorMessage = 'The meeting has been completed, it cannot be canceled nor marked as missed';
    case MissedMeetingErrorMessage = 'The meeting has been marked as missed';
}
