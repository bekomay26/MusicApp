<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\PostRepository::class, \App\Repositories\PostRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SongRepository::class, \App\Repositories\SongRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\GenreRepository::class, \App\Repositories\GenreRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\FavoriteRepository::class, \App\Repositories\FavoriteRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AlbumRepository::class, \App\Repositories\AlbumRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaylistRepository::class, \App\Repositories\PlaylistRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserPlaylistRepository::class, \App\Repositories\UserPlaylistRepositoryEloquent::class);
        //:end-bindings:
    }
}
