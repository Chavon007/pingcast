<?php

namespace Modules\Weather\App\Providers;

use Modules\Weather\App\Console\Commands\SendWeatherReports;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Weather\App\Interfaces\SubscriptionRepositoryInterface;
use Modules\Weather\App\Repositories\SubscriptionRepository;
use Modules\Weather\App\Interfaces\ReportLogRepositoryInterface;
use Modules\Weather\App\Repositories\ReportLogRepository;


class WeatherServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Weather';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'weather';

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(ReportLogRepositoryInterface::class, ReportLogRepository::class);    
        }

        
    /**
     * Bootstrap the application events.
     */

    public function boot(): void
    {
        parent::boot();

        if($this->app->runningInConsole()){
            $this->commands([
                SendWeatherReports::class,
            ]);
        }
    }
}