<?php

namespace Yesccx\DatabaseLogger\Listeners;

use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Yesccx\DatabaseLogger\Supports\LoggerContext;
use Yesccx\DatabaseLogger\Supports\LoggerDispatcher;
use Yesccx\DatabaseLogger\Supports\ResolverDispatcher;

/**
 * 执行记录器 监听
 */
final class ExecuteLoggerListener
{
    /**
     * 记录器上下文
     *
     * @var LoggerContext
     */
    protected $loggerContext;

    public function __construct()
    {
        $this->loggerContext = LoggerContext::make();
    }

    /**
     * @param QueryExecuted $query
     * @return void
     */
    public function handle(QueryExecuted $query): void
    {
        if (!$this->isEnabled() || $this->loggerContext->isQuietly()) {
            return;
        }

        try {
            $resolverDispatcher = new ResolverDispatcher;

            $resolverDispatcher->setRawQuery($query)->dispatch();

            LoggerDispatcher::make()->write(
                $resolvingResult = $resolverDispatcher->getResolvingResult()
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 是否为(记录器)开启状态
     *
     * @return bool
     */
    protected function isEnabled()
    {
        return config('database-logger.enabled', false);
    }
}
