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
final class Song extends Model implements Transformable
{
    use TransformableTrait;

    protected $casts = ['adapter_type_id' => 'integer'];

    public $rules = [
        'name'            => 'required|string',
        'description'     => 'required|string',
        'image_url'       => 'string',
        'song_path'       => 'string',
        'genre_id' => 'required|exists:genres,id',
        'album_id' => 'nullable|exists:albums,id',
        // 'artist_id' => 'required|exists:users,id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'image_url', 'song_path', 'genre_id', 'album_id'];


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
