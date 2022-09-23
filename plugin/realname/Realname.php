<?php
namespace modules\realname;

use app\common\model\Config;
use app\common\library\Menu;
use app\admin\model\MenuRule;
class Realname
{
    /**
     * 安装模块时执行的方法
     */
    public function install()
    {
        // 往后台常规管理内添加一个菜单
        $pMenu = MenuRule::where('name', 'routine')->value('id');
        $menu  = [
            [
                'type'      => 'menu',
                'title'     => '实名信息',
                'name'      => 'userRealname',
                'path'      => 'userRealname',
                'icon'      => 'fa fa-smile-o',
                'menu_type' => 'tab',
                'component' => '/src/views/backend/userRealname/index.vue',
                'keepalive' => '0',
                'pid'       => $pMenu ? $pMenu : 0,
                'children'  => [
                    ['type' => 'button', 'title' => '查看', 'name' => 'userRealname/index'],
                    ['type' => 'button', 'title' => '添加', 'name' => 'userRealname/add'],
                    ['type' => 'button', 'title' => '编辑', 'name' => 'userRealname/edit'],
                    ['type' => 'button', 'title' => '删除', 'name' => 'userRealname/del'],
                    ['type' => 'button', 'title' => '快速排序', 'name' => 'userRealname/sortable'],
                ],
            ]
        ];
        Menu::create($menu);

    }
    public function enable()
    {
        Config::addConfigGroup('idcard', '二要素接口');
        Menu::enable('userRealname');
    }

    public function disable()
    {
        Config::whereIn('name', ['secretId', 'secretKey'])->delete();
        Config::removeConfigGroup('idcard');
        Menu::disable('userRealname');
    }
    public function uninstall()
    {
        // 删除添加的菜单
        Menu::delete('userRealname', true);
    }
}