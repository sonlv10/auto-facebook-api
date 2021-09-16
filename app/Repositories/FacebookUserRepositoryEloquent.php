<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FacebookUserRepository;
use App\Entities\FacebookUser;
use App\Validators\FacebookUserValidator;
use Illuminate\Support\Facades\Http;


/**
 * Class FacebookUserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FacebookUserRepositoryEloquent extends BaseRepository implements FacebookUserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FacebookUser::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FacebookUserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function loginGetCookie($data)
    {
        $response = Http::asForm()->post('https://m.facebook.com/login.php', [
            'email' => $data['email'],
            'pass' => $data['pass'],
        ]);

        dd($response);
    }
}
