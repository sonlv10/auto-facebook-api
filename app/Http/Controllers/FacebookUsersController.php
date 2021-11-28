<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FacebookUserCreateRequest;
use App\Http\Requests\FacebookUserUpdateRequest;
use App\Repositories\FacebookUserRepository;
use App\Validators\FacebookUserValidator;

/**
 * Class FacebookUsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class FacebookUsersController extends Controller
{
    /**
     * @var FacebookUserRepository
     */
    protected $repository;

    /**
     * @var FacebookUserValidator
     */
    protected $validator;

    /**
     * FacebookUsersController constructor.
     *
     * @param FacebookUserRepository $repository
     * @param FacebookUserValidator $validator
     */
    public function __construct(FacebookUserRepository $repository, FacebookUserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $facebookUsers = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $facebookUsers,
            ]);
        }

        return view('facebookUsers.index', compact('facebookUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FacebookUserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FacebookUserCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $facebookUser = $this->repository->create($request->all());

            $response = [
                'message' => 'FacebookUser created.',
                'data'    => $facebookUser->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $facebookUser = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $facebookUser,
            ]);
        }

        return view('facebookUsers.show', compact('facebookUser'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facebookUser = $this->repository->find($id);

        return view('facebookUsers.edit', compact('facebookUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FacebookUserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $facebookUser = $this->repository->update($request->all(), $data['id']);

            $response = [
                'message' => 'FacebookUser updated.',
                'data'    => $facebookUser->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'FacebookUser deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'FacebookUser deleted.');
    }

    public function login(Request $request)
    {
        $user = $this->repository->loginGetCookie($request->all());
        return $user;
    }

    public function getPageContent(Request $request)
    {
        $contentXml = $this->repository->getPageContent($request->all());

        return response($contentXml);
    }

    public function getUserInfo(Request $request)
    {
        $response = [
            'success' => false,
            'data' => null,
        ];
        $data = $request->all();
        $user = $this->repository->fetchUserByCookie($data);
        if (!empty($user)) {
            $response = [
                'success' => true,
                'data' => $user,
            ];
        }
        return response($response);
    }

    public function getUserFriends(Request $request)
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        $data = $request->all();
        $userFriends = $this->repository->getUserFriends($data);
        if (!empty($userFriends)) {
            $response = [
                'success' => true,
                'data' => $userFriends,
            ];
        }
        return response($response);
    }

    public function getListUsers(Request $request)
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        $user = $request->user();
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $facebookUsers = $this->repository->with('proxy')->findWhere(['user_id' => $user->id]);

        if (!empty($facebookUsers)) {
            $response = [
                'success' => true,
                'data' => $facebookUsers,
            ];
        }
        return response()->json($response);
    }

    public function post(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Failed to post!',
        ];
        $data = $request->all();
        $post = $this->repository->post($data);
        if (!empty($post)) {
            $response = [
                'success' => true,
                'message' => 'Action completed!',
            ];
        }
        return response()->json($response);
    }

    public function get2fa(Request $request)
    {
        $response = [
            'success' => false,
            'message' => 'Failed to post!',
        ];
        $data = $request->all();
        $token = $this->repository->get2fa($data);
        if (!empty($token)) {
            $response = [
                'success' => true,
                'message' => 'Action completed!',
                'data' => $token
            ];
        }
        return response()->json($response);
    }

    public function storeUsers(Request $request)
    {
        $users = $data = $request->all();
        foreach ($users as $user) {
            try {
                $this->validator->with($user)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($user);
            } catch (ValidatorException $e) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'error'   => true,
                        'message' => $e->getMessageBag()
                    ]);
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Action completed!',
        ]);
    }
}
