<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_logs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('自增id');
            $table->longText('ua')->nullable()->comment('User-Agent');
            $table->text('url')->nullable()->comment('请求地址');
            $table->string('database_type', 32)->default('')->comment('数据库类型;mysql,mongo');
            $table->text('execute_sql')->nullable()->comment('执行的SQL语句');
            $table->bigInteger('execute_time')->default(0)->comment('执行耗时(纳秒)');
            $table->string('foramt_execute_time', 64)->default('')->comment('格式化的执行耗时');
            $table->longText('meta_data')->nullable()->comment('元信息');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_logs');
    }
}
