<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Genre.
 *
 * @package namespace App\Entities;
 */
final class Genre extends Model implements Transformable
{
    use TransformableTrait;

    public $rules = [
        'name'                  => 'required|string',
        'description'           => 'required|string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

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
