<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 商户管理
 *
 */
class Merchant extends Backend
{
    /**
     * Merchant模型对象
     * @var \app\admin\model\Merchant
     */
    protected $model = null;
    
    protected $quickSearchField = ['id'];

    protected $defaultSortField = 'weigh,desc';

    protected $preExcludeFields = ['createtime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Merchant;
    }

}