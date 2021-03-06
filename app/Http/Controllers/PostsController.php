<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PostCreateRequest;
use App\Presenters\PostPresenter;
use App\Http\Requests\PostUpdateRequest;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;

/**
 * Class PostsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PostsController extends Controller
{
    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @var PostValidator
     */
    protected $validator;

    /**
     * PostsController constructor.
     *
     * @param PostRepository $repository
     * @param PostValidator $validator
     */
    public function __construct(PostRepository $repository, PostValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getComments(Request $request)
    {
        $response = $this->repository->getComments($request->all());
        if (!empty($response['data'])) {
            $response['data'] = app(PostPresenter::class)->present($response['data'])['data'];
        }
        return response()->json($response);
    }

    public function getAllComments(Request $request)
    {
        $response = $this->repository->getAllComments($request->all());

        return response()->json($response);
    }

    public function FindId(Request $request)
    {
        $response = $this->repository->findId($request->get('url'));

        return response()->json($response);
    }
}
