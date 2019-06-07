<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\GenreCreateRequest;
use App\Http\Requests\GenreUpdateRequest;
use App\Repositories\GenreRepository;
use App\Validators\GenreValidator;

/**
 * Class GenresController.
 *
 * @package namespace App\Http\Controllers;
 */
class GenresController extends Controller
{
    /**
     * @var GenreRepository
     */
    protected $repository;

    /**
     * @var GenreValidator
     */
    protected $validator;

    /**
     * GenresController constructor.
     *
     * @param GenreRepository $repository
     * @param GenreValidator $validator
     */
    public function __construct(GenreRepository $repository, GenreValidator $validator)
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
        $this->repository->pushCriteria(app(\Prettus\Repository\Criteria\RequestCriteria::class));
        $genres = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $genres,
            ]);
        }

        return view('genres.index', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GenreCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(GenreCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $genre = $this->repository->create($request->all());

            $response = [
                'message' => 'Genre created.',
                'data'    => $genre->toArray(),
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $genre = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $genre,
            ]);
        }

        return view('genres.show', compact('genre'));
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
        $genre = $this->repository->find($id);

        return view('genres.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GenreUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(GenreUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $genre = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Genre updated.',
                'data'    => $genre->toArray(),
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Genre deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Genre deleted.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        // $field = request()->query('field');
        $value = request()->query('value');

        // $model = $this->repository->findByField($field, $value);
        $model = $this->repository->findByField('name', $value);
        // TODO: Maybe from model - format to show genre details and all the songs under it
        return response()->json([
            'data' => $model,
        ]);
    }
}
