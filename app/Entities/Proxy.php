<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Proxy.
 *
 * @package namespace App\Entities;
 */
class Proxy extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'jos_proxy_list';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'host',
        'port',
        'user_name',
        'password',
        'type'
    ];

}
