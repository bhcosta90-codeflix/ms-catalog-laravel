<?php

namespace App\Providers;

use App\Repositories\Eloquent\CastMemberRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\Modules\CastMember\Repositories\CastMemberRepositoryInterface;
use Costa\Core\Modules\Category\Repositories\CategoryRepositoryInterface;
use Costa\Core\Modules\Genre\Repositories\GenreRepositoryInterface;
use Costa\Core\Utils\Contracts\TransactionInterface;
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
        $this->app->singleton(CastMemberRepositoryInterface::class, CastMemberRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionDatabase::class);
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
