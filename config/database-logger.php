<?php

return [
    /**
     * 日志记录总开关
     */
    'enabled' => env('DL_ENABLED', false),

    /**
     * 日志记录器 驱动类型
     *
     * file - 文件
     * mysql - 数据库
     * ...
     */
    'logger' => env('DL_LOGGER_DRIVER', 'file'),

    /**
     * 自定义配置
     */
    'options' => [

        /**
         * MysqlLogger 连接名
         */
        'mysql_connection' => 'mysql',

        /**
         * 为 MysqlLogger 使用独立的连接
         * PS: 这将为日志记录器额外建立一次MySQL连接，因此会影响一定的性能
         */
        'connection_isolation' => env('DL_CONNECTION_ISOLATION', false),

    ],
];
