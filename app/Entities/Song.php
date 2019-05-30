<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Song.
 *
 * @package namespace App\Entities;
 */
class Song extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public $rules = [
        'name'            => 'required|string',
        'description'     => 'required|string',
        'image'           => 'string',
        'album_id' => 'required|exists:adapter_types,id',
        'artist_id' => 'required|exists:adapter_types,id'
    ];

    protected $casts = ['adapter_type_id' => 'integer'];
}
