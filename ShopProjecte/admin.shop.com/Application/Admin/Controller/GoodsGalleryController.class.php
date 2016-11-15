<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;

/**
 * Description of GoodsGalleryController
 *
 * @author Administrator
 */
class GoodsGalleryController extends \Think\Controller{
    public function remove($id) {
        /**
         * tp比较聪明,当它发现是ajax请求的时候,会自动返回json字符串
         */
        if(M('GoodsGallery')->delete($id)===false){
            $this->error('删除失败');
        }else{
            $this->success('删除成功');
        }
    }
}
