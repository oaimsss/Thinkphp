<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/28
 * Time: 15:41
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleCategoryController extends Controller {
    /**
     * @var \Admin\Model\ArticleCategoryModel
     */
    private $_model = null;

    /**
     * tp给我们提供的一个钩子挂件,可以在new控制器对象的时候自动执行.
     */
    protected function _initialize(){
        $this->_model = D('ArticleCategory');
    }
    /**
     * 用于展示文章分类列表
     * 1.创建文章分类模型
     * 2.获取文章分类列表
     * 3.传递文章分类数据到视图
     * 4.在视图中遍历展示
     *
     * TODO:搜索  分页
     */
    public function index() {
        //1.创建模型
        //2.获取列表
        $cond = [
            'status'=>['egt',0],
        ];
        //2.1获取查询关键字
        $keyword = I('get.keyword');
        if($keyword){
            $cond['name']=['like','%'.$keyword.'%'];
        }
        //获取分页数据和分页工具条
        $data = $this->_model->getPageResult($cond);
        //3.传递列表
        $this->assign($data);
        $this->display();
    }

    /**
     * 完成文章分类的添加表单的展示和数据处理
     * 1.展示表单
     * 2.提交表单
     *  获取数据
     *  数据验证
     *  存储到数据库
     */
    public function add() {
        if (IS_POST) {
            //得到一个模型
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加
            if ($this->_model->add() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            $this->display();
        }
    }

    /**
     * 文章分类回显和执行修改
     * 1.回显
     *  获取主键对应的记录
     *  展示出来
     * 2.修改
     *  接收数据
     *  验证
     *  更新到数据库
     *  跳转
     */
    public function edit($id) {
        if (IS_POST) {
            //获取数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            if ($this->_model->save() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //取出数据
            $row = $this->_model->find($id);
            $this->assign('row', $row);
            $this->display('add');
        }
    }

    /**
     * 删除文章分类
     * 物理删除delete($id)
     */
    public function remove($id) {
        if ($this->_model->delete($id) === false) {
            $this->error(get_error($this->_model));
        }else{
            //跳转
            $this->success('删除成功', U('index'));
        }
    }
}
