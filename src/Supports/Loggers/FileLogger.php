<?php

namespace Yesccx\DatabaseLogger\Supports\Loggers;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Yesccx\DatabaseLogger\Contracts\LoggerContract;
use Yesccx\DatabaseLogger\Supports\ResolvingResult;

/**
 * 日志记录器-文件
 */
class FileLogger implements LoggerContract
{
    /**
     * 输出描述信息状态
     * PS: 针对每次请求，仅输出一次描述信息
     *
     * @var bool
     */
    protected static $outputDecorationStatus = false;

    /**
     * 针对此次请求的UUID
     *
     * @var bool
     */
    protected static $requestUuid = '';

    /**
     * 日志通道
     *
     * @var LoggerInterface
     */
    protected $logChannel = null;

    public function __construct()
    {
        $this->initLogChannel();
    }

    /**
     * 写入日志
     *
     * @param ResolvingResult $resolvingResult
     * @return void
     */
    public function write(ResolvingResult $resolvingResult)
    {
        $this->printDecorationContent();

        // 每次日志记录时，标记UUID
        $this->getLogChannel()->info(
            sprintf(
                '[%s][%s] %s',
                static::$requestUuid,
                $resolvingResult->getFormatExecuteTime(),
                $resolvingResult->getExecuteSql()
            )
        );
    }

    /**
     * 输出 装饰信息
     *
     * @return void
     */
    protected function printDecorationContent()
    {
        $logChannel = $this->getLogChannel();

        // 对每次请求，仅输出一次装饰信息
        if (false === static::$outputDecorationStatus) {
            static::$requestUuid = str_random(5);

            $logChannel->info('');
            $logChannel->info('--------------------------------------------------------------------');
            $logChannel->info('UUID:   ' . static::$requestUuid);
            $logChannel->info('URL:    ' . request()->url());
            $logChannel->info('--------------------------------------------------------------------');

            static::$outputDecorationStatus = true;
        }
    }

    /**
     * 初始化 日志通道
     *
     * @return void
     */
    protected function initLogChannel()
    {
        $channelName = 'dl_file';

        $this->logChannel = Log::channel($channelName);
    }

    /**
     * 获取日志通道
     *
     * @return LoggerInterface
     */
    protected function getLogChannel()
    {
        return $this->logChannel;
    }
}
