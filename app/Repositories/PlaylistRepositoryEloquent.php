<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaylistRepository;
use App\Entities\Playlist;
use App\Validators\PlaylistValidator;

/**
 * Class PlaylistRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PlaylistRepositoryEloquent extends BaseRepository implements PlaylistRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Playlist::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PlaylistValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
