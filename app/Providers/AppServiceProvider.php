<?php

namespace App\Providers;

use App\Enums\RoleStatus;
use App\Observers\PostObserver;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('admin', function (User $user) {
            return $user->role === RoleStatus::ADMIN;
        });

        Gate::define('user', function (User $user) {
            return $user->role === RoleStatus::USER;
        });
        
        Builder::macro('whereAny', function (array $conditions) {
            /** @var Builder $this */
            return $this->where(function (Builder $query) use ($conditions) {
                foreach ($conditions as $condition) {
                    $query->orWhere(...$condition);
                }
            });
        });

        Post::observe(PostObserver::class);

    }

}
