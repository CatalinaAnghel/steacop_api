<?php
declare(strict_types=1);

namespace App\Validator;

use App\Dto\Rating\Input\CreateRatingInputDto;
use App\Entity\GuidanceMeeting;
use App\Validator\Contracts\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Security;

class RatingValidator implements ValidatorInterface {
    public function __construct(private readonly Security $security) {
    }

    public const MEETING_NOT_COMPLETED_ERROR = 'The meeting cannot be rated yet since it has not been completed';

    /**
     * @param $data
     * @param GuidanceMeeting $referencedObject
     * @return void
     */
    public function validate($data, $referencedObject = null): void {
        if (null !== $referencedObject && !$referencedObject->isCompleted()) {
            throw new UnprocessableEntityHttpException(self::MEETING_NOT_COMPLETED_ERROR);
        }

        if (!$data instanceof CreateRatingInputDto &&
            $data->getUser()->getEmail() !== $this->security->getUser()?->getUserIdentifier()) {
            throw new AccessDeniedHttpException('The rating cannot be updated');
        }
    }
}
