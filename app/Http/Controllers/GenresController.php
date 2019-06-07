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
       

        $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

        $genre = $this->repository->create($request->all());

        $response = [
            'message' => 'Genre created.',
            'data'    => $genre->toArray(),
        ];

        return response()->json($response);
    }    
}
