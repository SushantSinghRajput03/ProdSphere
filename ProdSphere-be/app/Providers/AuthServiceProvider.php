<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
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
        $this->configurePolicies();
    }

    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    protected function configurePolicies()
    {
        foreach ($this->policies as $model => $policy) {
            $this->app->bind($model, function ($app) use ($policy) {
                return new $policy($app->make(Product::class));
            });
        }
    }
} 