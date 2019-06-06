<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaylistCreateRequest;
use App\Http\Requests\PlaylistUpdateRequest;
use App\Repositories\PlaylistRepository;
use App\Validators\PlaylistValidator;

/**
 * Class PlaylistsController.
 *
 * @package namespace App\Http\Controllers;
 */
class PlaylistsController extends Controller
{
    /**
     * @var PlaylistRepository
     */
    protected $repository;

    /**
     * @var PlaylistValidator
     */
    protected $validator;

    /**
     * PlaylistsController constructor.
     *
     * @param PlaylistRepository $repository
     * @param PlaylistValidator $validator
     */
    public function __construct(PlaylistRepository $repository, PlaylistValidator $validator)
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
        $playlists = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $playlists,
            ]);
        }

        return view('playlists.index', compact('playlists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaylistCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(PlaylistCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $playlist = $this->repository->create($request->all());

            $response = [
                'message' => 'Playlist created.',
                'data'    => $playlist->toArray(),
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
        $playlist = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $playlist,
            ]);
        }

        return view('playlists.show', compact('playlist'));
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
        $playlist = $this->repository->find($id);

        return view('playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PlaylistUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PlaylistUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $playlist = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Playlist updated.',
                'data'    => $playlist->toArray(),
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
                'message' => 'Playlist deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Playlist deleted.');
    }
}
