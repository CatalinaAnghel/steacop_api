<?php
declare(strict_types=1);

namespace App\Dto\Traits;

use App\Dto\User\Output\UserDto;

trait UserTrait {
    /**
     * @var UserDto $user
     */
    private UserDto $user;

    /**
     * @return UserDto
     */
    public function getUser(): UserDto {
        return $this->user;
    }

    /**
     * @param UserDto $user
     */
    public function setUser(UserDto $user): void {
        $this->user = $user;
    }
}
