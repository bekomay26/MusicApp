<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 *
 * @package namespace App\Entities;
 */
final class User extends Authenticatable implements Transformable
{
    use TransformableTrait;
    use Billable;
    use Notifiable;

    public $rules = [
        'name'                  => 'required|string',
        'email'                  => 'required|string',
        'password'             => 'required|string',
        'is_artiste'            => 'required|integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'is_artiste', 'api_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'api_token'];

    


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_artiste' => 'boolean'
    ];

}
