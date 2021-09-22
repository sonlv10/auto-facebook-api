<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FacebookUser.
 *
 * @package namespace App\Entities;
 */
class FacebookUser extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'facebook_users';

    protected $casts = [
        'cookies' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'fb_uid',
        'name',
        'avatar',
        'cookies',
        'access_token',
    ];

}
