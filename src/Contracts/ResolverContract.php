<?php

namespace Yesccx\DatabaseLogger\Contracts;

use Illuminate\Database\Events\QueryExecuted;

/**
 * SQL解析器
 */
interface ResolverContract
{
    /**
     * 处理解析
     *
     * @param QueryExecuted $query
     * @return string|bool|null
     */
    public function handle(QueryExecuted $query);
}
