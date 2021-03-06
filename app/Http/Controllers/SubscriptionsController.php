<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SubscriptionCreateRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Repositories\SubscriptionRepository;
use App\Validators\SubscriptionValidator;
use App\Entities\User;

/**
 * Class SubscriptionsController.
 *
 * @package namespace App\Http\Controllers;
 */
class SubscriptionsController extends Controller
{
    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * @var SubscriptionValidator
     */
    protected $validator;

    /**
     * SubscriptionsController constructor.
     *
     * @param SubscriptionRepository $repository
     * @param SubscriptionValidator $validator
     */
    public function __construct(SubscriptionRepository $repository, SubscriptionValidator $validator)
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
        $subscriptions = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $subscriptions,
            ]);
        }

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriptionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(SubscriptionCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $subscription = $this->repository->create($request->all());

            $response = [
                'message' => 'Subscription created.',
                'data'    => $subscription->toArray(),
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
        $subscription = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $subscription,
            ]);
        }

        return view('subscriptions.show', compact('subscription'));
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
        $subscription = $this->repository->find($id);

        return view('subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SubscriptionUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(SubscriptionUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $subscription = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Subscription updated.',
                'data'    => $subscription->toArray(),
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
                'message' => 'Subscription deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Subscription deleted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // dd($request->user()['id']);
        $id = $request->user()['id'];
        $user = User::find($id);
        // dd($user);
        if ($user->subscribed('main')) {
            return response()->json([
                'message' => 'User has already subscribed.'
            ]);
        }
        $token = $request->all();
        $token = 'cus_FC6WsvErSugAtq';
        $user->newSubscription('main', 'plan_FC6pc0YZ6q5HOH')->create();
        return response()->json([
            'message' => 'User Subscribed to the premium plan.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadLatestInvoice(Request $request)
    {
        $id = $request->user()['id'];
        $user = User::find($id);
        $invoices = $user->invoices();
        $invoiceId = $invoices->last()->id;
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor'  => 'LMS Music Inc.',
            'product' => 'Music',
        ]);
    }
}
