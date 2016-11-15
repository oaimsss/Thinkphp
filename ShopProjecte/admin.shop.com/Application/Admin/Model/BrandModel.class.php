<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-5 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Model;

/**
 * Description of BrandModel
 *
 * @author kunx <kunx-edu@qq.com>
 */
class BrandModel extends \Think\Model{
    
    //自动验证规则
    protected $_validate = [
        ['name','require','品牌名称不能为空'],
    ];
    
    /**
     * 获取分页数据
     */
    public function getPageResult(array $cond = []) {
        $cond = array_merge(['status'=>['neq',-1]],$cond);
        //获取分页工具条
        $count = $this->where($cond)->count();
        $page = new \Think\Page($count, C('PAGE.SIZE'));
        $page->setConfig('theme', C('PAGE.THEME'));
        $page_html = $page->show();
        //获取分页数据
        $rows = $this->where($cond)->page(I('get.p'),C('PAGE.SIZE'))->order('sort')->select();
        //返回数据
        return [
            'page_html'=>$page_html,
            'rows'=>$rows,
        ];
    }
    
    /**
     * 获取所有可用的的品牌.
     * @return array
     */
    public function getList() {
        return $this->where(['status'=>1])->order('sort')->select();
    }
}
