## v1.2.1

- MySQL驱动记录器可单独指定连接，并对连接进行了隔离。

## v1.2.0

### Feature

- MySQL驱动记录器会记录接口的请求地址(如果存在的话)，因此为`database_logs`表新增了`url`字段，包版本在`1.2.0`版本之前的项目，可手动执行以下语句进行表结构兼容。
    ```sql
    ALTER TABLE `database_logs` ADD `url` text null COMMENT '请求地址' AFTER `ua`;
    ```

## v1.1.0

### Fixed

- 解决某些情况下异常无法捕获的问题