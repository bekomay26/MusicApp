<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Favorite.
 *
 * @package namespace App\Entities;
 */
class Favorite extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'song_id'];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'song_id' => 'required|exists:songs,id'
    ];

    protected $casts = ['user_id' => 'integer', 'song_id' => 'integer'];
}
