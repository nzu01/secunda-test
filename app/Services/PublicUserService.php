<?php

namespace App\Services;

use App\Models\PublicUser;

class PublicUserService
{
    /**
     * Найти пользователя по токену.
     *
     * @param string $token
     * @return PublicUser|null
     */
    public function getUserByToken(string $token): ?PublicUser
    {
        return PublicUser::query()
            ->where(PublicUser::API_TOKEN, $token)
            ->first();
    }
}
