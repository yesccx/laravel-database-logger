<?php

namespace Yesccx\DatabaseLogger\Supports\Resolvers;

use Illuminate\Database\Events\QueryExecuted;
use Yesccx\DatabaseLogger\Contracts\ResolverContract;

/**
 * 解析器-PDO解析
 *
 * PS: PDO模式参考的laravel/telescope包中的QueryWatcher
 * @see https://github.dev/laravel/telescope/blob/4.x/src/Watchers/QueryWatcher.php
 */
class PdoResolver implements ResolverContract
{
    /**
     * 处理解析
     *
     * @param QueryExecuted $query
     * @return string|bool|null
     */
    public function handle(QueryExecuted $query)
    {
        $sql = $query->sql;

        foreach ($this->formatBindings($query) as $key => $binding) {
            $regex = is_numeric($key)
                ? "/\?(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

            if ($binding === null) {
                $binding = 'null';
            } elseif (is_int($binding) || is_float($binding)) {
                $binding = (string) $binding;
            } else {
                $binding = $this->quoteStringBinding($query, $binding);
            }

            $sql = preg_replace($regex, $binding, $sql, is_numeric($key) ? 1 : -1);
        }

        return $sql;
    }

    /**
     * Format the given bindings to strings.
     *
     * @param QueryExecuted $event
     * @return array
     */
    protected function formatBindings(QueryExecuted $event): array
    {
        return $event->connection->prepareBindings($event->bindings);
    }

    /**
     * Add quotes to string bindings.
     *
     * @param QueryExecuted $event
     * @param string $binding
     * @return string
     */
    protected function quoteStringBinding(QueryExecuted $event, string $binding): string
    {
        try {
            return $event->connection->getPdo()->quote($binding);
        } catch (\PDOException $e) {
            return '';
        }

        // Fallback when PDO::quote function is missing...
        $binding = \strtr($binding, [
            chr(26) => '\\Z',
            chr(8)  => '\\b',
            '"'     => '\"',
            "'"     => "\'",
            '\\'    => '\\\\',
        ]);

        return "'" . $binding . "'";
    }
}
