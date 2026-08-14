<?php

namespace App\Providers;

use App\Models\ServiceCategory;
use App\Policies\ContentPolicy;
use App\Support\SettingsRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('leads', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('admin-login', fn (Request $request) => Limit::perMinute(5)->by(mb_strtolower((string) $request->input('email')).'|'.$request->ip()));
        Gate::define('manage-content', [ContentPolicy::class, 'manage']);
        Gate::define('view-leads', [ContentPolicy::class, 'viewLeads']);
        View::composer('*', function ($view): void {
            $view->with('siteSettings', app(SettingsRepository::class)->public());
        });
        View::composer(['partials.header', 'partials.footer'], function ($view): void {
            $categories = Schema::hasTable('service_categories')
                ? Cache::remember('navigation.service-categories', now()->addMinutes(20), fn () => ServiceCategory::query()
                    ->where('is_active', true)
                    ->with(['services' => fn ($query) => $query->published()->orderBy('sort_order')])
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn (ServiceCategory $category): array => [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'services' => $category->services->map(fn ($service): array => [
                            'name' => $service->name,
                            'slug' => $service->slug,
                        ])->all(),
                    ])->all())
                : collect();
            $view->with('navServiceCategories', $categories);
        });
    }
}
