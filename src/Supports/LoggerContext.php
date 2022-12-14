<?php

namespace Yesccx\DatabaseLogger\Supports;

/**
 * 记录器-上下文
 */
class LoggerContext
{
    /**
     * 静默锁 栈
     * PS: 利用锁栈的形式防止嵌套锁的情况下异常
     *
     * @var array
     */
    protected $quietlyLockStack = [];

    /**
     * instance
     *
     * @var static
     */
    protected static $instance;

    protected function __construct()
    {
    }

    /**
     * make singleton instance
     *
     * @return static
     */
    public static function make()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * 开启 静默锁
     *
     * @return void
     */
    public function quietlyLock()
    {
        array_push($this->quietlyLockStack, true);
    }

    /**
     * 解除 静默锁
     *
     * @return void
     */
    public function unQuietlyLock()
    {
        array_shift($this->quietlyLockStack);
    }

    /**
     * 是否为静默状态
     *
     * @return bool
     */
    public function isQuietly()
    {
        return !empty($this->quietlyLockStack);
    }
}
