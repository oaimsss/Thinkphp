<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;

/**
 * Description of GoodsController
 *
 * @author Administrator
 */
class GoodsController extends \Think\Controller {

    /**
     * @var \Admin\Model\GoodsModel
     */
    private $_model = null;

    /**
     * 控制器初始化执行的方法.
     */
    protected function _initialize() {
        $this->_model = D('Goods');
    }

    /**
     * 商品列表
     */
    public function index() {
        
        //1.创建模型
        //2.获取数据
        //2.1获取查询条件
        $cond = [
            'status' => 1,
        ];
        
        //2.1.1获取商品分类
        $goods_category_id = I('get.goods_category_id');
        if($goods_category_id){
            $cond['goods_category_id'] = $goods_category_id;
        }
        //2.1.2获取品牌
        $brand_id = I('get.brand_id');
        if($brand_id){
            $cond['brand_id'] = $brand_id;
        }
        //2.1.1获取商品促销状态
        $goods_status = I('get.goods_status');
        if($goods_status){
            $cond[] = 'goods_status & ' . $goods_status;
        }
        //2.1.1获取商品是否上架
        $is_on_sale = I('get.is_on_sale');
        if(strlen($is_on_sale)){ //或者$is_on_sale !== ''
            $cond['is_on_sale'] = $is_on_sale;
        }
        
        //2.1.2获取商品名称关键字
        $keyword = I('get.keyword');
        if($keyword){
            $cond['name'] = ['like','%'.$keyword.'%'];
        }
        
        
        //2.2获取分页数据及分页html代码
        $this->assign($this->_model->getPageResult($cond));

        //获取所有的分类
        $goods_cateogry_model = D('GoodsCategory');
        $goods_categories     = $goods_cateogry_model->getList();
        $this->assign('goods_categories',$goods_categories);
        //获取所有的品牌
        $brand_model          = D('Brand');
        $brands               = $brand_model->getList();
        $this->assign('brands', $brands);
        
        //3.传递数据
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            //1.收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //2.添加商品
            if ($this->_model->addGoods() === false) {
                $this->error(get_error($this->_model));
            }
            //3.跳转
            $this->success('添加成功', U('index'));
        } else {


            $this->_before_view();
            $this->display();
        }
    }

    public function edit($id) {
        if(IS_POST){
            //1.收集数据
            if ($this->_model->create() === false) {
                $this->error(get_error($this->_model));
            }
            //2.添加商品
            if ($this->_model->saveGoods() === false) {
                $this->error(get_error($this->_model));
            }
            //3.跳转
            $this->success('修改成功', U('index'));
        }else{
            //获取数据
            $row = $this->_model->getGoodsInfo($id);
            //传递给视图
            $this->assign('row', $row);
            $this->_before_view();
            $this->display('add');
        }
    }

    public function remove($id) {
        
    }

    private function _before_view() {
        //获取商品的分类
        $goods_cateogry_model = D('GoodsCategory');
        $goods_categories     = $goods_cateogry_model->getList();
        $this->assign('goods_categories', json_encode($goods_categories));
        
        //获取品牌列表
        $brand_model          = D('Brand');
        $brands               = $brand_model->getList();
        $this->assign('brands', $brands);
    }

}
