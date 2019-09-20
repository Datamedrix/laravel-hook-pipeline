<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of an application or library developed by Datamedrix and
 * is subject to the provisions of your License Agreement with
 * Datamedrix GmbH.
 *
 * @copyright (c) 2019 Datamedrix GmbH
 * ----------------------------------------------------------------------------
 * @author Christian Graf <c.graf@datamedrix.com>
 */

declare(strict_types=1);

namespace DMX\Application\Pipeline;

use DMX\Application\Pipeline\Contracts\DispatcherInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     * {@inheritdoc}
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->app->bind(DispatcherInterface::class, HookDispatcher::class);
        $this->app->singleton(HookDispatcher::class);
    }

    /**
     * Get the services provided by the provider.
     * {@inheritdoc}
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function provides()
    {
        return [
            DispatcherInterface::class,
            HookDispatcher::class,
        ];
    }
}
