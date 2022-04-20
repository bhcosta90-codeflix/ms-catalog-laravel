<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CategoryRepository,
    GenreRepository
};
use Costa\Core\Domains\Repositories\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};

use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\UseCases\Contracts\TransactionContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(GenreRepositoryInterface::class, GenreRepository::class);
        $this->app->bind(TransactionContract::class, TransactionDatabase::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
