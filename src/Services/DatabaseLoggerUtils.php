<?php

namespace Yesccx\DatabaseLogger\Services;

use Yesccx\DatabaseLogger\Supports\LoggerContext;

class DatabaseLoggerUtils
{
    /**
     * 不使用记录器的情况下执行闭包
     *
     * @param callable $callback
     * @return mixed
     */
    public static function withoutLogger($callback)
    {
        $loggerContext = LoggerContext::make();

        try {
            $loggerContext->quietlyLock();

            return $callback();
        } finally {
            $loggerContext->unQuietlyLock();
        }
    }
}
