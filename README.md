<h1 align="center">Database-Logger</h1>
<p align="center">记录数据库MySQL、Mongo等执行日志，支持多数Laravel版本</p>

## 目录
- [目录](#目录)
- [功能特点](#功能特点)
  - [运行环境](#运行环境)
- [开始使用](#开始使用)
  - [1. 安装](#1-安装)
  - [2. 初始化](#2-初始化)
  - [3. 配置](#3-配置)
  - [4. 使用](#4-使用)
- [API](#api)
  - [临时禁用日志记录](#临时禁用日志记录)
  - [自定义记录器](#自定义记录器)
- [功能清单](#功能清单)
- [License](#license)


## 功能特点

- 支持多数Laravel版本：`Laravel 5+`、`Laravel 6+`、`Laravel 7+`、`Laravel 8+`、`Laravel 9+`
- 支持`MongoDB`、`MySQL`产生的执行日志
- 可选日志存储驱动`file`、`mysql`，并支持自定义扩展
- 更多API支持


### 运行环境

| PHP版本      |
| ------------ |
| PHP >= 5.6.4 |

 | Laravel版本 | 包版本 | 支持状态 |
 | :---------- | :----- | :------- |
 | 9.x         | 1.0.x  | 支持     |
 | 8.x         | 1.0.x  | 支持     |
 | 7.x         | 1.0.x  | 支持     |
 | 6.x         | 1.0.x  | 支持     |
 | 5.8.x       | 1.0.x  | 支持     |
 | 5.7.x       | 1.0.x  | 支持     |
 | 5.6.x       | 1.0.x  | 支持     |


## 开始使用

### 1. 安装

```shell
composer require yesccx/laravel-databae-logger:2.*
```

### 2. 初始化

发布配置文件，ServiceProvider等。

```shell
> php artisan database-logger:install

# Publishing Service Provider... [app/Provider/DatabaseLoggerProvider.php]
# Publishing Configuration... [config/database-logger.php]
# Database Logger installed successfully.
```

如果需要将日志记录到数据表中，还需要提前初始化对应的`日志记录表`。
```shell
> php artisan database-logger:migration

# Publishing Migration... [database/migrations/2022_12_06_194505_create_database_logs_table.php]
# Migration created successfully!

> php artisan migrate --path=database/migrations/2022_12_06_194505_create_database_logs_table.php
```

### 3. 配置

从`env`配置中开启记录器，并指定记录器的驱动。
> PS: 如果以数据库驱动，需要先准备好数据表。在初始化时执行相关的迁移文件即可

```
> .env

# 日志记录总开关
DL_ENABLED=true

# 日志记录器 驱动类型
# file - 文件
# mysql - 数据库
DL_LOGGER_DRIVER=file
```

### 4. 使用

- 以 `file` 驱动时日志内容会存储至`storage/logs/dl_sql-*.log`文件中；
- 以 `mysql` 驱动时，日志内容会存储至`database_logs`数据表中。

## API

### 临时禁用日志记录

如果想在某些情况下临时禁用日志记录功能，可以执行以下操作：

```php
use Yesccx\DatabaseLogger\Services\DatabaseLoggerUtils;

DatabaseLoggerUtils::withoutLogger(function() {
    // 该闭包内执行的SQL语句将不会产生日志记录
    \App\Models\User::query()->first();
});
```

### 自定义记录器

当需要将日志内容以别的方式记录时，可以通过自定义记录器实现

**1. 定义记录器：**
```php
namespace App\Services\DatabaseLogger;

use Yesccx\DatabaseLogger\Contracts\LoggerContract;
use Yesccx\DatabaseLogger\Supports\ResolvingResult;

class MongoLogger implements LoggerContract
{
    public function write(ResolvingResult $resolvingResult)
    {
        // 100ms 格式化的执行耗时
        $formatExecuteTime = $resolvingResult->getFormatExecuteTime();

        // 1000000 执行耗时(纳秒)
        $executeTime = $resolvingResult->getExecuteTime();

        // SELECT .... 执行的SQL语句
        $executeSql = $resolvingResult->getExecuteSql();

        // 编写存储逻辑
    }
}
```

**2. 使用自定义记录器：**
```
>env

# 日志记录器 驱动类型
DL_LOGGER_DRIVER=App\Services\DatabaseLogger\MongoLogger
```

## 功能清单

- ~~记录数据日志~~
- ~~可选数据日志记录的存储驱动(file、mysql)~~
- ~~赋于查询时可禁用日志记录功能~~
- 赋于模型查询时直接打印SQL语句
- 日志记录事件
- 可视化面板查看日志
- 补充更多API
- 单元测试

## License

MIT
