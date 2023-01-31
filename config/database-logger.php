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

    ],
];
