<?php

namespace Yesccx\DatabaseLogger\Supports;

use Illuminate\Database\Events\QueryExecuted;
use Yesccx\DatabaseLogger\Contracts\ResolvingResultContract;

/**
 * SQL解析结果
 */
class ResolvingResult implements ResolvingResultContract
{
    /**
     * 原始查询
     *
     * @var QueryExecuted
     */
    protected $rawQuery;

    /**
     * 执行的SQL语句
     *
     * @var string
     */
    protected $executeSql;

    /**
     * @param QueryExecuted $rawQuery 原始查询
     * @param string $executeSql 执行的SQL语句
     */
    public function __construct(QueryExecuted $rawQuery, string $executeSql)
    {
        $this->rawQuery = $rawQuery;
        $this->executeSql = $executeSql;
    }

    /**
     * 获取 执行SQL
     *
     * @return string
     */
    public function getExecuteSql()
    {
        return $this->executeSql;
    }

    /**
     * 获取 原始查询
     *
     * @return QueryExecuted
     */
    public function getRawQuery()
    {
        return $this->rawQuery;
    }

    /**
     * 获取 执行耗时
     *
     * @return int|float
     */
    public function getExecuteTime()
    {
        $rawQuery = $this->getRawQuery();

        return !empty($rawQuery) ? $rawQuery->time * 1000 : 0;
    }

    /**
     * 获取 格式化后的执行耗时
     *
     * @return string
     */
    public function getFormatExecuteTime()
    {
        $milliseconds = $this->getExecuteTime();

        if ($milliseconds < 1000) {
            $milliseconds = round($milliseconds) . 'μs';
        } elseif ($milliseconds < 10000000) {
            $milliseconds = round($milliseconds / 1000, 2) . 'ms';
        } else {
            $milliseconds = round($milliseconds / 1000 / 1000, 2) . 's';
        }

        return $milliseconds;
    }
}
