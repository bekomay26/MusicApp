<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\FavoriteCreateRequest;
use App\Http\Requests\FavoriteUpdateRequest;
use App\Repositories\FavoriteRepository;
use App\Validators\FavoriteValidator;

/**
 * Class FavoritesController.
 *
 * @package namespace App\Http\Controllers;
 */
class FavoritesController extends Controller
{
    /**
     * @var FavoriteRepository
     */
    protected $repository;

    /**
     * @var FavoriteValidator
     */
    protected $validator;

    /**
     * FavoritesController constructor.
     *
     * @param FavoriteRepository $repository
     * @param FavoriteValidator $validator
     */
    public function __construct(FavoriteRepository $repository, FavoriteValidator $validator)
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
        $favorites = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $favorites,
            ]);
        }

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FavoriteCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(FavoriteCreateRequest $request)
    {
        dd('dfd');
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $favorite = $this->repository->create([
                'song_id' => $request['song_id'],
                'user_id' => $request->user()['id']
            ]);

            // $favorite = $this->repository->create($request->all());

            $response = [
                'message' => 'Song added to Favourites',
                'data'    => $favorite->toArray(),
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
        $favorite = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $favorite,
            ]);
        }

        return view('favorites.show', compact('favorite'));
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
        $favorite = $this->repository->find($id);

        return view('favorites.edit', compact('favorite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FavoriteUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(FavoriteUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $favorite = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Favorite updated.',
                'data'    => $favorite->toArray(),
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
                'message' => 'Favorite deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Favorite deleted.');
    }
}
