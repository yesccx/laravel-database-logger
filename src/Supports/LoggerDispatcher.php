<?php

namespace Yesccx\DatabaseLogger\Supports;

use Yesccx\DatabaseLogger\Contracts\LoggerContract;
use Yesccx\DatabaseLogger\Exceptions\DatabaseLoggerException;

/**
 * 记录器调度器
 */
class LoggerDispatcher
{
    /**
     * alias
     *
     * @var array
     */
    protected $loggerAlias = [
        'file'  => \Yesccx\DatabaseLogger\Supports\Loggers\FileLogger::class,
        'mysql' => \Yesccx\DatabaseLogger\Supports\Loggers\MysqlLogger::class,
    ];

    /**
     * make instance
     *
     * @return static
     */
    public static function make()
    {
        return new static;
    }

    /**
     * 写入 解析结果
     *
     * @param ResolvingResult $resolvingResult
     * @return void
     */
    public function write($resolvingResult)
    {
        $this->getLoggerInstance()->write($resolvingResult);
    }

    /**
     * 获取 记录器实例
     *
     * @return LoggerContract
     */
    protected function getLoggerInstance()
    {
        $logger = config('database-logger.logger', '');

        if (empty($logger)) {
            throw new DatabaseLoggerException('not found database logger');
        }

        if (isset($this->loggerAlias[$logger])) {
            $loggerClass = $this->loggerAlias[$logger];
        } else {
            $loggerClass = $logger;
        }

        if (!class_exists($loggerClass)) {
            throw new DatabaseLoggerException('database logger is not a class');
        } elseif (!is_subclass_of($loggerClass, LoggerContract::class)) {
            throw new DatabaseLoggerException('database logger need to implement LoggerContract');
        }

        return new $loggerClass;
    }
}
