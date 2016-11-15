<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/30
 * Time: 9:30
 */

namespace Admin\Controller;


use Think\Controller;

class ArticleController extends Controller {
    /**
     * @var \Admin\Model\ArticleModel
     */
    private $_model = null;

    /**
     * tp给我们提供的一个钩子挂件,可以在new控制器对象的时候自动执行.
     */
    protected function _initialize() {
        $this->_model = D('Article');
    }

    /**
     * 展示文章列表,并展示出文章的分类名称
     */
    public function index() {
        //获取查询条件
        $keyword = I('get.keyword');
        $cond    = [ ];
        if ($keyword) {
            $cond['name'] = [ 'like', '%' . $keyword . '%' ];
        }
        //调用模型中自定义分页方法
        $data = $this->_model->getPageResult($cond);
        $this->assign($data);
        $this->display();
    }

    /**
     * 添加文章,保存基本信息和详细内容
     */
    public function add() {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加数据
            if ($this->_model->addArticle() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('添加成功', U('index'));
        } else {
            //取出所有的文章分类
            $article_categories = D('ArticleCategory')->getList();
            $this->assign('article_categories', $article_categories);
            $this->display();
        }
    }

    /**
     * 修改文章
     *
     * @param integer $id 文章id.
     */
    public function edit($id) {
        if (IS_POST) {
            //收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //添加数据
            if ($this->_model->saveArticle() === false) {
                $this->error(get_error($this->_model));
            }
            //跳转
            $this->success('修改成功', U('index'));
        } else {
            //获取文章信息.
            $row = $this->_model->getArticleInfo($id);
            $this->assign('row', $row);

            //取出所有的文章分类
            $article_categories = D('ArticleCategory')->getList();
            $this->assign('article_categories', $article_categories);
            $this->display('add');
        }
    }

    /**
     * 删除文章,并删除文章详细内容.
     *
     * @param integer $id 文章id.
     */
    public function remove($id) {
        if ($this->_model->deleteArticle($id) === false) {
            $this->error(get_error($this->_model));
        } else {
            $this->success('删除成功', U('index'));
        }
    }

}