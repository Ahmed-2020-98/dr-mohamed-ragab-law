<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Support\Settings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(Settings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Data the public header and footer need on every page.
        View::composer(['components.site-header', 'components.site-footer'], function ($view): void {
            static $shared = null;

            $shared ??= [
                'hasPosts' => Post::published()->exists(),
                'footerServices' => Service::active()->limit(7)->get(['id', 'title', 'slug']),
                'footerPages' => Page::where('is_active', true)->get(['id', 'title', 'slug']),
            ];

            $view->with($shared);
        });
    }
}
