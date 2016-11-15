<?php

namespace Admin\Controller;

class AdminController extends \Think\Controller {

    /**
     * @var \Admin\Model\AdminModel
     */
    private $_model = null;

    protected function _initialize() {
        $this->_model = D('Admin');
    }

    public function index() {
        //获取查询信息
        $keyword = trim(I('get.keyword'));
        //调用查询方法
        $cond    = [];
        if ($keyword) {
            $cond['username'] = [
                'like', '%' . $keyword . '%'
            ];
        }
        //传递查询结果到模板
        $this->assign($this->_model->getPageResult($cond));
        //展示模板
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create('', 'reg') === false) {
                $this->error(get_error($this->_model));
            }
            //添加管理员
            if ($this->_model->addAdmin() === false) {
                $this->error(get_error($this->_model));
            }
            //成功跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->_before_view();
            $this->display();
        }
    }

    public function edit($id) {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //修改管理员
            if ($this->_model->saveAdmin($id) === false) {
                $this->error(get_error($this->_model));
            }
            //成功跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取管理员信息
            $row = $this->_model->getAdminInfo($id);
            $this->assign('row', $row);
            
            $this->_before_view();
            $this->display('add');
        }
    }

    public function remove($id) {
        if ($this->_model->deleteAdmin($id) === false) {
            $this->error(get_error($this->_model));
        }
        //成功跳转
        $this->success('删除成功', U('index'));
    }

    public function login() {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->login() === false) {
                $this->error(get_error($this->_model));
            }
            //成功跳转到后台首页Index/index
            $this->success('登录成功', U('Index/index'));
        } else {

            $this->display();
        }
    }

    public function logout() {
        session(null);
        cookie(null);
        $this->success('退出成功', U('login'));
    }

    private function _before_view() {
        //获取所有的角色
        $roles = D('Role')->getList();
        $this->assign('roles', json_encode($roles));
    }

}
