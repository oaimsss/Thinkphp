<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-8 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Controller;

/**
 * Description of GoodsCategoryController
 *
 * @author kunx <kunx-edu@qq.com>
 */
class GoodsCategoryController extends \Think\Controller {

    /**
     * @var \Admin\Model\GoodsCategoryModel
     */
    private $_model;

    public function _initialize() {
        $this->_model = D('GoodsCategory');
    }

    /**
     * 获取分类列表。
     */
    public function index() {
        $rows = $this->_model->getList();
        $this->assign('rows', $rows);
        $this->display();
    }

    /**
     * 添加分类
     */
    public function add() {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            if ($this->_model->addCategory() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('添加成功', U('index'));
        } else {
            //获取已有分类，以便选择父级
            $this->_before_view();
            $this->display();
        }
    }

    /**
     * 修改分类。
     * @param integer $id 分类id。
     */
    public function edit($id) {
        if (IS_POST) {
            if ($this->_model->create() === false) {
                $this->error($this->_model->getError());
            }
            if ($this->_model->saveCategory() === false) {
                $this->error($this->_model->getError());
            }
            $this->success('修改成功', U('index'));
        } else {
            $row = $this->_model->find($id);
            $this->assign('row', $row);

            $this->_before_view();
            $this->display('add');
        }
    }

    /**
     * 移除分类及其后代分类。
     * @param integer $id
     */
    public function remove($id) {
        if ($this->_model->deleteCategory($id) === false) {
            $this->error($this->_model->getError());
        }
        $this->success('删除成功', U('index'));
    }

    /**
     * 获取分类列表，传递json字符串，以便ztree使用。
     */
    private function _before_view() {
        //获取已有分类，以便选择父级
        $rows       = $this->_model->getList();
        array_unshift($rows, ['id' => 0, 'name' => '顶级分类']);
        $categories = json_encode($rows);
        $this->assign('categories', $categories);
    }

}
