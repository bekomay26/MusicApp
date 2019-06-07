<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Rating.
 *
 * @package namespace App\Entities;
 */
final class Rating extends Model implements Transformable
{
    use TransformableTrait;

    public $rules = [
        'score'            => 'required|integer',
        'comment'     => 'string|nullable',
        'song_id' => 'required|exists:songs,id',
        'user_id' => 'required|exists:users,id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['score', 'comment', 'song_id', 'user_id'];
}
