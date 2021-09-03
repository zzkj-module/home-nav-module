<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateHomeNavTable extends Migration
{
    public $tableName = "home_nav";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) $this->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }

    /**
     * 执行创建表
     */
    private function create()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';      // 设置存储引擎
            $table->charset = 'utf8';       // 设置字符集
            $table->collation  = 'utf8_general_ci';       // 设置排序规则

            $table->id();
            $table->string('name', 100)->nullable(false)->default("")->comment("图标名称")->index("name_index");
            $table->string("route", 255)->nullable(false)->default("")->comment("跳转路由");
            $table->string("open_type", 255)->nullable(false)->default("")->comment("打开方式：跟 applet 表的打开方式一样");
            $table->string("pic", 255)->nullable(false)->default("")->comment("图标");
            $table->unsignedTinyInteger("sort")->nullable(false)->default(100)->comment("排序: 升序");
            $table->tinyInteger("is_show")->nullable(false)->default(1)->comment("是否显示：0=隐藏，1=显示");
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '小程序首页导航栏表'";
        DB::statement($qu);
    }
}
