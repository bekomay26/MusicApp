<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SongCreateRequest;
use App\Http\Requests\SongUpdateRequest;
use App\Repositories\SongRepository;
use App\Validators\SongValidator;

use Illuminate\Support\Facades\Storage;

/**
 * Class SongsController.
 *
 * @package namespace App\Http\Controllers;
 */
class SongsController extends Controller
{
    /**
     * @var SongRepository
     */
    protected $repository;

    /**
     * @var SongValidator
     */
    protected $validator;

    /**
     * SongsController constructor.
     *
     * @param SongRepository $repository
     * @param SongValidator $validator
     */
    public function __construct(SongRepository $repository, SongValidator $validator)
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
        $songs = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $songs,
            ]);
        }

        return view('songs.index', compact('songs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SongCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(SongCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $path = $request->file('song')->store('songs');
            // dd(array_merge($request->all(), ['song_path' => $path]));
            // dd($path);
            // $song = $this->repository->create($request->all());
            $song = $this->repository->create(array_merge($request->all(), ['song_path' => $path]));
            $response = [
                'message' => 'Song created.',
                'data'    => $song->toArray(),
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
        $song = $this->repository->find($id);
        // dd($song['song_path']);
        $contents = Storage::get($song['song_path']);
        return $contents;
        dd($contents);
        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Song retrieved',
                'data' => $song,
            ]);
        }

        return view('songs.show', compact('song'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $song = $this->repository->find($id);
        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Song downloading...',
                'data' => $song,
            ]);
        }

        return Storage::download($song['song_path']);
        // return view('songs.show', compact('song'));
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
        $song = $this->repository->find($id);

        return view('songs.edit', compact('song'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SongUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(SongUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $song = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Song updated.',
                'data'    => $song->toArray(),
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
        $song = $this->repository->find($id);
        $deleted = $this->repository->delete($id);
        Storage::delete($song['song_path']);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Song deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Song deleted.');
    }
}
