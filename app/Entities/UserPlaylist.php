<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class UserPlaylist.
 *
 * @package namespace App\Entities;
 */
class UserPlaylist extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public $rules = [
        'playlist_id'           => 'required|exists:adapter_types,id',
        'user_id' => 'required|exists:adapter_types,id'
    ];

    protected $casts = ['artist_id' => 'integer', 'playlist_id' => 'integer'];
}
