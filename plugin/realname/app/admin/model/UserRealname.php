<?php

namespace app\admin\model;

use think\Model;

/**
 * UserRealname
 * @controllerUrl 'userRealname'
 */
class UserRealname extends Model
{
    // 表主键
    protected $pk = 'uid';
    
    // 表名
    protected $name = 'user_realname';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 实名状态获取器
    public function getRealnameStatusAttr($value,$data): string
    {
        $status = [0=>'未实名',1=>'实名待验证',2=>'已实名',3=>'实名未通过'];
        if (empty($data['status'])){
            return $status[0];
        }
        return $status[$data['status']];
    }
}