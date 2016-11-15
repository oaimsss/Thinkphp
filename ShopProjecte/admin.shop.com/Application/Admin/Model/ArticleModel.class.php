<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/30
 * Time: 9:32
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleModel extends Model {

    //开启批量验证
    protected $patchValidate = true;
    /**
     * 自动验证规则
     * 1.名称必填   唯一
     * 2.状态值必须是0和1之间的一个
     */
    protected $_validate = [
        [ 'name', 'require', '文章名称不能为空' ],
        [ 'article_category_id', 'require', '文章分类不能为空' ],
        [ 'status', '0,1', '状态不合法', self::EXISTS_VALIDATE, 'in' ]
    ];

    /**
     * 获取分页数据和分页工具条代码
     *
     * @param array $cond 查询条件.
     *
     * @return array
     */
    public function getPageResult(array $cond) {
        $page_setting = C('PAGE');
        //拼凑一个连贯查询,where  page
//        $rows = $this->where($cond)->field('article.*,ac.name as acname')->page(I('get.p'),$page_setting['PAGE_SIZE'])->join('article_category as ac ON ac.id=article.article_category_id')->select();
        $rows = $this->where($cond)->page(I('get.p'), $page_setting['SIZE'])->select();
        //获取所有可用的分类
        $article_categories = D('ArticleCategory')->getList();
        //获取分页html代码
        //获取总行数
        $count = $this->where($cond)->count();
        $page  = new Page($count, $page_setting['SIZE']);
        //设置分页工具条的样式
        $page->setConfig('theme', $page_setting['THEME']);
        //获取分页工具条代码
        $page_html = $page->show();
        return compact('page_html', 'rows', 'article_categories');
    }

    /**
     * 添加文章
     *
     * @return bool
     */
    public function addArticle() {
        unset($this->data['id']);
        //保存基本信息,并获取文章id
        if (($id = $this->add()) === false) {
            return false;
        }
        //保存文章详细信息
        $data = [
            'article_id' => $id,
            'content' => I('post.content'),
        ];
        if (M('ArticleContent')->add($data) === false) {
            $this->error = '保存详细内容失败';
            return false;
        }
        return $id;
    }

    /**
     * 修改文章
     *
     * @return bool
     */
    public function saveArticle() {
        $id = $this->data['id'];
        //保存基本信息,并获取文章id
        if ($this->save() === false) {
            return false;
        }
        //保存文章详细信息
        $data = [
            'article_id' => $id,
            'content' => I('post.content'),
        ];
        if (M('ArticleContent')->save($data) === false) {
            $this->error = '保存详细内容失败';
            return false;
        }
        return $id;
    }

    /**
     * 获取文章及详细内容
     *
     * @param integer $id 文章id.
     *
     * @return array 文章信息.
     */
    public function getArticleInfo($id) {
        return $this->field('a.*,ac.content')->alias('a')->join('__ARTICLE_CONTENT__ as ac ON ac.article_id = a.id')->find($id);
    }

    /**
     * 删除文章及详细内容.
     *
     * @param integer $id 文章id.
     *
     * @return bool
     */
    public function deleteArticle($id) {
        if ($this->delete($id) === false) {
            return false;
        }
        if (M('ArticleContent')->delete($id) === false) {
            $this->error = '删除详细内容失败';
            return false;
        }
        return true;
    }
}