<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GenreRepository;
use App\Entities\Genre;
use App\Validators\GenreValidator;

/**
 * Class GenreRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
final class GenreRepositoryEloquent extends BaseRepository implements GenreRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Genre::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return GenreValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
