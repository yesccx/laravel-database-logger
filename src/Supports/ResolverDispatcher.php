<?php

namespace Yesccx\DatabaseLogger\Supports;

use Exception;
use Illuminate\Database\Events\QueryExecuted;

/**
 * SQL解析调度器
 */
class ResolverDispatcher
{
    /**
     * 原始查询
     *
     * @var QueryExecuted
     */
    protected $rawQuery;

    /**
     * 解析结果
     *
     * @var ResolvingResult
     */
    protected $resolvingResult;

    /**
     * SQL解析器集
     *
     * @var array
     */
    protected static $sqlResolvers = [];

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
     * 指定 原始查询
     *
     * @param QueryExecuted $rawQuery
     * @return $this
     */
    public function setRawQuery($rawQuery)
    {
        $this->rawQuery = $rawQuery;

        return $this;
    }

    /**
     * 解析调度
     *
     * @return $this
     */
    public function dispatch()
    {
        $executeSql = $this->rawQuery->sql;

        foreach (static::getSqlResolvers() as $resolver) {
            try {
                $executeSql = (new $resolver())->handle($this->rawQuery);
            } catch (Exception $e) {
                $executeSql = null;
            }

            if (!(is_null($executeSql) || false === $executeSql)) {
                break;
            }
        }

        $this->stashResolvingResult((string) $executeSql);

        return $this;
    }

    /**
     * 获取 解析结果
     *
     * @return ResolvingResult
     */
    public function getResolvingResult()
    {
        return $this->resolvingResult;
    }

    /**
     * 暂存 解析结果
     *
     * @param string $executeSql 执行的SQL语句
     * @return void
     */
    protected function stashResolvingResult(string $executeSql)
    {
        $this->resolvingResult = new ResolvingResult($this->rawQuery, $executeSql);
    }

    /**
     * 获取 SQL解析器集
     *
     * @return array
     */
    public static function getSqlResolvers()
    {
        return static::$sqlResolvers;
    }

    /**
     * 设置 SQL解析器集
     *
     * @param array $data
     * @return void
     */
    public static function setSqlResolvers($data)
    {
        static::$sqlResolvers = $data;
    }
}
