<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AlbumCreateRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Repositories\AlbumRepository;
use App\Validators\AlbumValidator;

use App\Entities\Song;
use Illuminate\Http\Response;

/**
 * Class AlbumsController.
 *
 * @package namespace App\Http\Controllers;
 */
class AlbumsController extends Controller
{
    /**
     * @var AlbumRepository
     */
    protected $repository;

    /**
     * @var AlbumValidator
     */
    protected $validator;

    /**
     * AlbumsController constructor.
     *
     * @param AlbumRepository $repository
     * @param AlbumValidator $validator
     */
    public function __construct(AlbumRepository $repository, AlbumValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app(\Prettus\Repository\Criteria\RequestCriteria::class));
        $albums = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $albums,
            ]);
        }

        return view('albums.index', compact('albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlbumCreateRequest $request
     *
     * @return Response
     *
     * @throws ValidatorException
     */
    public function store(AlbumCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $album = $this->repository->create($request->all());

            // $album->songs()->attach(request('songs'));

            $response = [
                'message' => 'Album created.',
                'data'    => $album->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $validatorException) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $validatorException->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($validatorException->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $album = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $album,
            ]);
        }

        return view('albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $album = $this->repository->find($id);

        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlbumUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws ValidatorException
     */
    public function update(AlbumUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $album = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Album updated.',
                'data'    => $album->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $validatorException) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $validatorException->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($validatorException->getMessageBag())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlbumUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws ValidatorException
     */
    public function addSongs(AlbumUpdateRequest $request, $id)
    {
        try {
            // dd(request('songs'));
            // dd($request);
            $songs = Song::whereIn('id', request('songs'));
            // // dd($songs);
            $songs->update(['album_id' => $id]);
            // dd($this->repository->find($id));
            $album = $this->repository->find($id);
            // dd($album);
            // dd($album->toArray());
            // $album->songs()->update();
            // $album->songs()->attach(request('songs'));

            $response = [
                'message' => 'Songs added to album.',
                'data'    => $album->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $validatorException) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $validatorException->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($validatorException->getMessageBag())->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Album deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Album deleted.');
    }
}
