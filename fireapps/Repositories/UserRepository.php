<?php

namespace FireApps\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use FireApps\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return User::class;
    }
}
