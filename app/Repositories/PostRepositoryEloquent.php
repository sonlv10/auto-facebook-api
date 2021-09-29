<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Container\Container as Application;
use App\Repositories\PostRepository;
use App\Repositories\FacebookUserRepository;
use App\Entities\Post;
use App\Helpers\Common;
use App\Helpers\FacebookClient;
use App\Validators\PostValidator;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PostRepositoryEloquent extends BaseRepository implements PostRepository
{

    private $FbHelper;

    public function __construct(Application $app, Common $FbHelper)
    {
        $this->FbHelper = $FbHelper;
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PostValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAllComments($data)
    {
        $postComments = [];
        $fbClient = new FacebookClient();
        $endpoint = "https://graph.facebook.com/" . $data['post_id'] . "/comments?summary=1&filter=stream&access_token=" . $data['access_token'];
        do {
            $response = $fbClient->callGraphApi('GET', $endpoint);
            if (!empty($response['data'])) {
                $postComments = array_merge($postComments, $response['data']['data']);
            }
            $endpoint = $response['data']['paging']['next'] ?? null;

        } while (!empty($endpoint));

        return $postComments;
    }

    public function getComments($data)
    {
        $result = null;
        $fbClient = new FacebookClient();
        $endpoint = "https://graph.facebook.com/" . $data['post_id'] . "/comments?summary=1&filter=stream&access_token=" . $data['access_token'];
        if (!empty($data['next'])) {
            $endpoint = $data['next'];
        }
        $response = $fbClient->callGraphApi('GET', $endpoint);
        if (!empty($response['data'])) {
            $result = $response['data'];
        }

        return $result;
    }
    
}
