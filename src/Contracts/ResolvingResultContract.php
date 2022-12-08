<?php

namespace Yesccx\DatabaseLogger\Contracts;

/**
 * SQL解析结果
 */
interface ResolvingResultContract
{
    /**
     * 获取 执行SQL
     *
     * @return string
     */
    public function getExecuteSql();

    /**
     * 获取 原始查询
     *
     * @return mixed
     */
    public function getRawQuery();

    /**
     * 获取 执行耗时
     *
     * @return int|float
     */
    public function getExecuteTime();

    /**
     * 获取 格式化后的执行耗时
     *
     * @return string
     */
    public function getFormatExecuteTime();
}
