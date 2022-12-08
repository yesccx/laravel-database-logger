<?php

namespace Yesccx\DatabaseLogger\Contracts;

use Yesccx\DatabaseLogger\Supports\ResolvingResult;

/**
 * SQL记录器
 */
interface LoggerContract
{
    /**
     * 写入日志
     *
     * @param ResolvingResult $resolvingResult
     * @return void
     */
    public function write(ResolvingResult $resolvingResult);
}
