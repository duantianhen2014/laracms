<?php
/**
 * LaraCMS - CMS based on laravel
 *
 * @category  LaraCMS
 * @package   Laravel
 * @author    Wanglelecc <wanglelecc@gmail.com>
 * @date      2018/06/06 09:08:00
 * @copyright Copyright 2018 LaraCMS
 * @license   https://opensource.org/licenses/MIT
 * @github    https://github.com/wanglelecc/laracms
 * @link      https://www.laracms.cn
 * @version   Release 1.0
 */

namespace App\Models;

/**
 * 设置模型
 *
 * Class Setting
 * @package App\Models
 */
class Setting extends Model
{
    protected $fillable = [
        'owner', 'module', 'section','key','value',
    ];

    public $timestamps = false;

    /**
     * 取出
     *
     * @param $section
     * @param string $module
     * @param string $owner
     * @return mixed
     */
    public function take($section, $module = 'common', $owner = 'system'){
        return static::where(['owner'=>$owner,'module'=>$module,'section'=>$section,])->pluck('value','key')->toArray();
    }


    /**
     * 存储
     *
     * @param $data
     * @param $section
     * @param string $module
     * @param string $owner
     * @return bool
     */
    public function store($data, $section, $module = 'common', $owner = 'system'){
        foreach($data as $key => $value){
            empty($value) && $value = '';
            static::updateOrCreate(['owner'=>$owner,'module'=>$module,'section'=>$section,'key'=>$key], ['value'=> is_string($value) ? $value : json_encode($value)]);
        }

        return true;
    }

    /**
     * 将数据库中的配置信息注入到框架中
     */
    public static function afflux(){
        $config = [];
        foreach(static::get() as $item){
            $key = "{$item->owner}.{$item->module}.{$item->section}.{$item->key}";
            $config[$key] = $item->value;
        }

        config($config);
    }

}
