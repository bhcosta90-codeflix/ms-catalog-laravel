<?php

namespace App\Providers;

use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\Modules\Category\Repositories\CategoryRepositoryInterface;
use Costa\Core\Modules\Genre\Repositories\GenreRepositoryInterface;
use Costa\Core\Utils\UseCases\Contracts\TransactionContract;
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
