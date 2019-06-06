<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UserPlaylistCreateRequest;
use App\Http\Requests\UserPlaylistUpdateRequest;
use App\Repositories\UserPlaylistRepository;
use App\Validators\UserPlaylistValidator;

/**
 * Class UserPlaylistsController.
 *
 * @package namespace App\Http\Controllers;
 */
class UserPlaylistsController extends Controller
{
    /**
     * @var UserPlaylistRepository
     */
    protected $repository;

    /**
     * @var UserPlaylistValidator
     */
    protected $validator;

    /**
     * UserPlaylistsController constructor.
     *
     * @param UserPlaylistRepository $repository
     * @param UserPlaylistValidator $validator
     */
    public function __construct(UserPlaylistRepository $repository, UserPlaylistValidator $validator)
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
        $userPlaylists = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $userPlaylists,
            ]);
        }

        return view('userPlaylists.index', compact('userPlaylists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserPlaylistCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserPlaylistCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $userPlaylist = $this->repository->create($request->all());

            $response = [
                'message' => 'UserPlaylist created.',
                'data'    => $userPlaylist->toArray(),
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
        $userPlaylist = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $userPlaylist,
            ]);
        }

        return view('userPlaylists.show', compact('userPlaylist'));
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
        $userPlaylist = $this->repository->find($id);

        return view('userPlaylists.edit', compact('userPlaylist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserPlaylistUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UserPlaylistUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $userPlaylist = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'UserPlaylist updated.',
                'data'    => $userPlaylist->toArray(),
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
                'message' => 'UserPlaylist deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'UserPlaylist deleted.');
    }
}
