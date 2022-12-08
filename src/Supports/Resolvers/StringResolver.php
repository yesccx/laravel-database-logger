<?php

namespace Yesccx\DatabaseLogger\Supports\Resolvers;

use Illuminate\Database\Events\QueryExecuted;
use Yesccx\DatabaseLogger\Contracts\ResolverContract;

/**
 * 解析器-字符串解析
 */
class StringResolver implements ResolverContract
{
    /**
     * 处理解析
     *
     * @param QueryExecuted $query
     * @return string|bool|null
     */
    public function handle(QueryExecuted $query)
    {
        $record = str_replace('?', '"%s"', $query->sql);
        $record = vsprintf($record, $query->bindings);
        $record = str_replace('\\', '', $record);

        return $record;
    }
}
