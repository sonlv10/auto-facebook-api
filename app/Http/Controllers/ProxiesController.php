<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ProxyCreateRequest;
use App\Http\Requests\ProxyUpdateRequest;
use App\Repositories\ProxyRepository;
use App\Validators\ProxyValidator;

/**
 * Class ProxiesController.
 *
 * @package namespace App\Http\Controllers;
 */
class ProxiesController extends Controller
{
    /**
     * @var ProxyRepository
     */
    protected $repository;

    /**
     * @var ProxyValidator
     */
    protected $validator;

    /**
     * ProxiesController constructor.
     *
     * @param ProxyRepository $repository
     * @param ProxyValidator $validator
     */
    public function __construct(ProxyRepository $repository, ProxyValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $user = $request->user();
        $proxies = $this->repository->findWhere(['user_id' => $user->id]);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $proxies,
            ]);
        }

        return view('proxies.index', compact('proxies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProxyCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $user = $request->user();
            $data = $request->all();
            $data['user_id'] = $user->id;
            $proxy = $this->repository->create($data);

            $response = [
                'message' => 'Proxy created.',
                'data'    => $proxy->toArray(),
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
        $proxy = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $proxy,
            ]);
        }

        return view('proxies.show', compact('proxy'));
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
        $proxy = $this->repository->find($id);

        return view('proxies.edit', compact('proxy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProxyUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $data = $request->all();
            $proxy = $this->repository->update($request->all(), $data['id']);

            $response = [
                'message' => 'Proxy updated.',
                'data'    => $proxy->toArray(),
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
                'message' => 'Proxy deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Proxy deleted.');
    }
}
