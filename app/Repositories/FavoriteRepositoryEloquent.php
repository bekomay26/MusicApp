<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FavoriteRepository;
use App\Entities\Favorite;
use App\Validators\FavoriteValidator;

/**
 * Class FavoriteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
final class FavoriteRepositoryEloquent extends BaseRepository implements FavoriteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Favorite::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FavoriteValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
