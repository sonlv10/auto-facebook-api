<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FacebookUserRepository.
 *
 * @package namespace App\Repositories;
 */
interface FacebookUserRepository extends RepositoryInterface
{
    //
    public function loginGetCookie($data);

    public function fetchUserByCookie($data);

    public function getUserFriends($data);
}
