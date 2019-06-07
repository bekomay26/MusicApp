<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Album.
 *
 * @package namespace App\Entities;
 */
final class Album extends Model implements Transformable
{
    use TransformableTrait;

    protected $casts = ['artist_id' => 'integer'];

    public $rules = [
        'name'            => 'required|string',
        'description'     => 'required|string',
        'image_url'           => 'string',
        'artist_id' => 'required|exists:users,id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'image_url', 'artist_id'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['songs'];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
