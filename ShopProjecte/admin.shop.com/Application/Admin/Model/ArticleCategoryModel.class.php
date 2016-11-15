<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:31
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class ArticleCategoryModel extends Model{

    //开启批量验证
    protected $patchValidate = true;
    /**
     * 自动验证规则
     * 1.名称必填   唯一
     * 2.状态值必须是0和1之间的一个
     */
    protected $_validate = [
        ['name','require','文章分类名称不能为空'],
        ['name','','文章分类已存在',self::EXISTS_VALIDATE,'unique'],
        ['status','0,1','状态不合法',self::EXISTS_VALIDATE,'in']
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
        $rows = $this->where($cond)->page(I('get.p'),$page_setting['PAGE_SIZE'])->order('sort')->select();

        //获取分页html代码
        //获取总行数
        $count = $this->where($cond)->count();
        $page = new Page($count,$page_setting['SIZE']);
        //设置分页工具条的样式
        $page->setConfig('theme',$page_setting['THEME']);
        //获取分页工具条代码
        $page_html = $page->show();
        return compact('page_html','rows');
    }

    /**
     * 获取所有可用的文章分类.
     * @return array 文章分类列表.二维数组.
     */
    public function getList() {
        return $this->where(['status'=>1])->order('sort')->getField('id,id,name');
    }

}