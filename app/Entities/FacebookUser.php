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
        'cookies' => 'array',
        'params' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email',
        'password',
        'secret',
        'proxy_id',
        'fb_uid',
        'name',
        'avatar',
        'cookies',
        'access_token',
        'params',
    ];

    public function proxy()
    {
        return $this->hasOne(Proxy::class, 'id', 'proxy_id');
    }
}
