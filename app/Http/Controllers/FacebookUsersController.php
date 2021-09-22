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
    public function update(FacebookUserUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $facebookUser = $this->repository->update($request->all(), $id);

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
        $data = $request->all();
        $user = $this->repository->fetchUserByCookie($data);
        return response($user);
    }

    public function getUserFriends(Request $request)
    {
        $data = $request->all();
        $user = $this->repository->getUserFriends($data);
        return response($user);
    }
}
