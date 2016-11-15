<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-13 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Model;

/**
 * Description of PermissionModel
 *
 * @author kunx <kunx-edu@qq.com>
 */
class PermissionModel extends \Think\Model {

    //put your code here
    protected $patchValidate = true;
    protected $_validate = [
        ['name', 'require', '权限名称不能为空'],
        ['parent_id', 'require', '父级不能为空'],
    ];

    /**
     * 获取权限列表。
     * @return type
     */
    public function getList() {
        return $this->order('lft')->select();
    }

    /**
     * 添加权限。
     * @return boolean
     */
    public function addPermission() {
        //使用nestedsets完成左右节点和层级的计算。
        $orm        = new \Admin\Logic\MySQLORM;
        $nestedsets = new \Admin\Logic\NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
        if ($nestedsets->insert($this->data['parent_id'], $this->data, 'bottom') === false) {
            $this->error = '添加失败';
            return false;
        }
        return true;
    }

    /**
     * 保存权限。
     * @return boolean
     */
    public function savePermission() {
        //修改左右节点和层级
        //判断是否需要移动
        //获取db中的父级分类
        $parent_id  = $this->where(['id' => $this->data['id']])->getField('parent_id');
        if($parent_id != $this->data['parent_id']){
            //使用nestedsets完成左右节点和层级的计算。
            $orm        = new \Admin\Logic\MySQLORM;
            $nestedsets = new \Admin\Logic\NestedSets($orm, $this->getTableName(), 'lft', 'rght', 'parent_id', 'id', 'level');
            if ($nestedsets->moveUnder($this->data['id'], $this->data['parent_id'], 'bottom') === false) {
                $this->error = '不能将分类移动到自身或后代分类中';
                return false;
            }
        }

        //保存基本信息
        return $this->save();
    }

}
