<?php
/**
 * Created By PhpStorm.
 * User: Li Ming
 * Date: 2021-08-03
 * Fun: 商品表
 */

namespace Modules\HomeNav\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class HomeNav extends BaseModel
{
    use HasFactory;
    protected $table = "home_nav";

    /**
     * 显示
     * @return string[]
     */
    public static function getIsShowArr()
    {
        return [
            "1" => "显示",
            "0" => "隐藏",
        ];
    }
}