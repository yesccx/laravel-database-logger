<?php

namespace Yesccx\DatabaseLogger;

use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\ServiceProvider;
use Yesccx\DatabaseLogger\Console\InstallCommand;
use Yesccx\DatabaseLogger\Console\MigrationCommand;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerPublishing();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/database-logger.php',
            'database-logger'
        );

        $this->mergeLoggingConfigFrom(
            __DIR__ . '/../config/logging-channels.php'
        );
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
            MigrationCommand::class,
        ]);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/database-logger.php' => config_path('database-logger.php'),
        ], 'database-logger-config');

        $this->publishes([
            __DIR__ . '/../stubs/DatabaseLoggerProvider.stub' => app_path('Providers/DatabaseLoggerProvider.php'),
        ], 'database-logger-provider');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'database-logger-migrations');
    }

    /**
     * Merge the given logging channels configuration with the existing configuration.
     *
     * @param string $path
     * @return void
     */
    protected function mergeLoggingConfigFrom($path)
    {
        if (!($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            $configData = $config->get('logging', []);
            $configData['channels'] = array_merge($configData['channels'], require $path);

            $config->set('logging', $configData);
        }
    }
}
