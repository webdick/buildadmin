<?php

namespace app\admin\model;

use think\Model;

/**
 * Merchant
 * @controllerUrl 'merchant'
 */
class Merchant extends Model
{
    // 表名
    protected $name = 'merchant';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = false;


    protected static function onAfterInsert($model)
    {
        if ($model->weigh == 0) {
            $pk = $model->getPk();
            $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
        }
    }

}