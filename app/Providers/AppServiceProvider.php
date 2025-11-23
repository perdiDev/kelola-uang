<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Observers\ActivityLogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            Item::class,
            Transaction::class,
            TransactionCategory::class,
            Role::class,
            Permission::class,
        ] as $model) {
            $model::observe(ActivityLogObserver::class);
        }
    }
}
