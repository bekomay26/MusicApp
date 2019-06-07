<?php

namespace Tests;

use App\Entities\User;
use Illuminate\Support\Facades\Auth;

abstract class ControllerTestCase extends DBTestCase
{
    protected $user;

    /**
     * Setup the DB before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        if (!$user) {
            $user = factory(User::class)->create(['name' => 'Test User']);
        }

        $this->actingAs($user, 'api');
        $this->user = $user;
    }
    /**
     * Setup the Token Headers
     *
     * @param User $user
     *
     * @return array
     */
    public static function getHeaders(User $user)
    {
        $header = [];
        $header['Accept'] = 'application/json';
       
        $token = Auth::guard('api')->fromUser($user);
        $header['Authorization'] = 'Bearer ' . $token;

        return $header;
    }
}