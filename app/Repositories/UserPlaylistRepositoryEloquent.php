<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserPlaylistRepository;
use App\Entities\UserPlaylist;
use App\Validators\UserPlaylistValidator;

/**
 * Class UserPlaylistRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
final class UserPlaylistRepositoryEloquent extends BaseRepository implements UserPlaylistRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserPlaylist::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserPlaylistValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
