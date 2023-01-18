<?php

namespace Yesccx\DatabaseLogger\Supports\Loggers;

use Yesccx\DatabaseLogger\Contracts\LoggerContract;
use Yesccx\DatabaseLogger\Supports\ResolvingResult;
use Yesccx\DatabaseLogger\Models\DatabaseLog;

/**
 * 记录器-MYSQL
 */
class MysqlLogger implements LoggerContract
{
    /**
     * 使用的连接名称
     *
     * @var string
     */
    public static $connectName = '_dl-mysql';

    /*
     * 防止 记录器执行时触发 事件
     *
     * @var bool
     */
    protected static $lock = false;

    /**
     * 写入日志
     *
     * @param ResolvingResult $resolvingResult
     * @return void
     */
    public function write(ResolvingResult $resolvingResult)
    {
        $this->withLock(
            function () use ($resolvingResult) {
                if (config('database-logger.options.connection_isolation', false)) {
                    $builder = DatabaseLog::on(self::$connectName);
                } else {
                    $builder = DatabaseLog::query();
                }

                $builder->create([
                    'database_type'       => $resolvingResult->getRawQuery()->connection->getDriverName(),
                    'ua'                  => request()->userAgent(),
                    'url'                 => request()->url(),
                    'execute_sql'         => $resolvingResult->getExecuteSql(),
                    'execute_time'        => $resolvingResult->getExecuteTime(),
                    'foramt_execute_time' => $resolvingResult->getFormatExecuteTime(),
                ]);
            }
        );
    }

    /**
     * 需要锁定执行
     *
     * @param callable $callback
     * @return void
     */
    public function withLock($callback)
    {
        if (static::isLock()) {
            return;
        }

        static::lock();

        try {
            $callback();
        } finally {
            static::unLock();
        }
    }

    /**
     * 判断是否为锁定状态
     *
     * @return bool
     */
    protected function isLock()
    {
        return self::$lock;
    }

    /**
     * 锁定
     *
     * @return void
     */
    protected static function lock()
    {
        self::$lock = true;
    }

    /**
     * 取消锁定
     *
     * @return void
     */
    protected static function unLock()
    {
        self::$lock = false;
    }
}
