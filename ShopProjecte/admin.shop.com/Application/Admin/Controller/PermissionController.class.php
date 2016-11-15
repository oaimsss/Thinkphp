<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-13 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Controller;

/**
 * Description of PermissionController
 *
 * @author kunx <kunx-edu@qq.com>
 */
class PermissionController extends \Think\Controller {

    /**
     * @var Admin\Model\PermissionModel
     */
    private $_model;

    protected function _initialize() {
        $this->_model = D('Permission');
    }

    /**
     * 权限列表
     */
    public function index() {
        //获取权限列表
        $rows = $this->_model->getList();
        $this->assign('rows', $rows);
        $this->display();
    }

    /**
     * 添加权限
     */
    public function add() {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加
            if ($this->_model->addPermission() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改权限。
     * @param integer $id
     */
    public function edit($id) {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->savePermission() === false) {
                $this->error(get_error($this->_model));
            }
            $this->success('修改成功', U('index'));
        } else {
            //查找数据表中的数据
            $row = $this->_model->find($id);
            $this->assign('row', $row);
            //获取所有的权限
            $this->_before_view();
            //展示视图
            $this->display('add');
        }
    }
    
    /**
     * 删除权限及其后代权限。
     * @param type $id
     */
    public function remove($id) {
        
    }

    private function _before_view() {
        //获取已有权限列表
        $permissions = $this->_model->getList();
        array_unshift($permissions, ['id' => 0, 'name' => '顶级权限']);
        $this->assign('permissions', json_encode($permissions));
    }

}
