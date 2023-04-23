<?php
declare(strict_types=1);

namespace App\Validator;

use App\Dto\Meeting\Contracts\AbstractCancellableMeetingInputDto;
use App\Entity\Contracts\AbstractMeeting;
use App\Helper\MeetingValidationHelper;
use App\Validator\Contracts\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PatchMeetingValidator implements ValidatorInterface
{
    /**
     * @param AbstractCancellableMeetingInputDto $data
     * @param AbstractMeeting $referencedObject
     * @return void
     */
    public function validate($data, $referencedObject = null): void
    {
        if (null !== $referencedObject) {
            if ($referencedObject->isCanceled()) {
                throw new UnprocessableEntityHttpException(
                    MeetingValidationHelper::CanceledMeetingErrorMessage->value
                );
            }

            if ($referencedObject->isCompleted() && $data->getIsCanceled()) {
                throw new UnprocessableEntityHttpException(
                    MeetingValidationHelper::CompletedMeetingErrorMessage->value
                );
            }
        }
    }
}
