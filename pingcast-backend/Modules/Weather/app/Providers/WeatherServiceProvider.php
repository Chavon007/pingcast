<?php

namespace Modules\Weather\App\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Modules\Weather\App\Interfaces\SubscriptionRepositoryInterface;
use Modules\Weather\App\Repositories\SubscriptionRepository;

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
    }
}