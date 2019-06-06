<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\RatingCreateRequest;
use App\Http\Requests\RatingUpdateRequest;
use App\Repositories\RatingRepository;
use App\Validators\RatingValidator;

/**
 * Class RatingsController.
 *
 * @package namespace App\Http\Controllers;
 */
class RatingsController extends Controller
{
    /**
     * @var RatingRepository
     */
    protected $repository;

    /**
     * @var RatingValidator
     */
    protected $validator;

    /**
     * RatingsController constructor.
     *
     * @param RatingRepository $repository
     * @param RatingValidator $validator
     */
    public function __construct(RatingRepository $repository, RatingValidator $validator)
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
        $ratings = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $ratings,
            ]);
        }

        return view('ratings.index', compact('ratings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RatingCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(RatingCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            // dd($request->user()['id']);
            $rating = $this->repository->create([
                'score' => $request['score'],
                'comment' => $request['comment'],
                'song_id' => $request['song_id'],
                'user_id' => $request->user()['id'],
            ]);

            $response = [
                'message' => 'Rating created.',
                'data'    => $rating->toArray(),
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
        $rating = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $rating,
            ]);
        }

        return view('ratings.show', compact('rating'));
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
        $rating = $this->repository->find($id);

        return view('ratings.edit', compact('rating'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RatingUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(RatingUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $rating = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Rating updated.',
                'data'    => $rating->toArray(),
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
                'message' => 'Rating deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Rating deleted.');
    }
}
