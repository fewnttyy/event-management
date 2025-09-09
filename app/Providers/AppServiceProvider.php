<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\AuthRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\EventRepository;
use App\Services\EventService;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Services\BookingService;

use Carbon\CarbonInterval;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AuthService::class, function ($app) {
            return new AuthService($app->make(AuthRepositoryInterface::class));
        });

        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(EventService::class, function ($app) {
            return new EventService($app->make(EventRepositoryInterface::class));
        });

        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BookingService::class, function ($app) {
            return new BookingService($app->make(BookingRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });

        // Passport::tokensExpireIn(CarbonInterval::days(15));
        // Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        // Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
