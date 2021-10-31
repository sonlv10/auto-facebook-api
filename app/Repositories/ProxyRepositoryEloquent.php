<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProxyRepository;
use App\Entities\Proxy;
use App\Validators\ProxyValidator;

/**
 * Class ProxyRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProxyRepositoryEloquent extends BaseRepository implements ProxyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Proxy::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
