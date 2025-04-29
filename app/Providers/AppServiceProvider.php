<?php

namespace App\Providers;

use App\Interfaces\BreadcrumbsFromUrlInterface;
use App\Interfaces\FormServiceInterface;
use App\Interfaces\FormTemplateServiceInterface;
use App\Services\BreadcrumbsService;
use App\Services\FormService;
use App\Services\FormTemplateService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BreadcrumbsFromUrlInterface::class, BreadcrumbsService::class);
        $this->app->bind(FormTemplateServiceInterface::class, FormTemplateService::class);
        $this->app->bind(FormServiceInterface::class, FormService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

    }
}
