<?php

namespace Yesccx\DatabaseLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;
use Yesccx\DatabaseLogger\Listeners\ExecuteLoggerListener;
use Yesccx\DatabaseLogger\Supports\ResolverDispatcher;
use Yesccx\DatabaseLogger\Supports\Resolvers\PdoResolver;
use Yesccx\DatabaseLogger\Supports\Resolvers\StringResolver;

class LoggerApplicationServiceProvider extends ServiceProvider
{
    /**
     * Init
     *
     * @return void
     */
    protected function initDatabaseLogger()
    {
        if (config('database-logger.enabled', false)) {
            $this->registerEvents();

            $this->initSqlResolvers();
        }
    }

    /**
     * Register events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        if (class_exists(QueryExecuted::class) && $this->app->bound('events')) {
            $this->app->make('events')->listen(QueryExecuted::class, ExecuteLoggerListener::class);
        }
    }

    /**
     * Init sql resolvers
     *
     * @return void
     */
    protected function initSqlResolvers()
    {
        ResolverDispatcher::setSqlResolvers($this->sqlResolvers());
    }

    /**
     * Define sql resolvers
     *
     * @return array
     */
    protected function sqlResolvers()
    {
        return [
            PdoResolver::class,
            StringResolver::class,
        ];
    }
}
