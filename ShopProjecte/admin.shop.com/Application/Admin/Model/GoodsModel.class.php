<?php

namespace Admin\Model;

/**
 * Description of GoodsModel
 *
 * @author Administrator
 */
class GoodsModel extends \Think\Model {

    protected $patchValidate = true;
    protected $_validate     = [
            ['name', 'require', '商品名称不能为空'],
            ['goods_category_id', 'require', '分类不能为空'],
            ['brand_id', 'require', '品牌不能为空'],
            ['supplier_id', 'require', '供应商不能为空'],
            ['shop_price', 'currency', '商城价不能为空'],
            ['market_price', 'currency', '市场价不能为空'],
            ['stock', 'require', '库存不能为空'],
            ['is_on_sale', '0,1', '上架状态不合法', self::EXISTS_VALIDATE, 'in'],
            ['sort', 'require', '排序不能为空'],
    ];
    protected $_auto         = [
            ['inputtime', NOW_TIME],
            ['goods_status', 'calcGoodsStatus', self::MODEL_BOTH, 'callback'],
    ];

    /**
     * 自动将促销状态进行按位或.
     * @param array|null $goods_statuses 商品促销状态.
     * @return int
     */
    protected function calcGoodsStatus($goods_statuses) {
        if (is_array($goods_statuses)) {
            return array_sum($goods_statuses);
        } else {
            return 0;
        }
    }

    /**
     * 
     */
    public function addGoods() {
        unset($this->data['id']);
        //开启事务
        $this->startTrans();
        //货号的生成SN2016080200001
        $this->_calcSn();
        //保存商品基本信息,并获取商品id.
        if (($goods_id = $this->add()) === false) {
            $this->rollback();
            return false;
        }
        //保存商品描述
        $goods_intro_model = M('GoodsIntro');
        $data              = [
            'goods_id' => $goods_id,
            'content'  => I('post.content', '', false),
        ];
        if ($goods_intro_model->add($data) === false) {
            $this->error = '商品详情保存失败';
            $this->rollback();
            return false;
        }
        //保存商品的相册
        $pathes = I('post.path');
        $data   = [];
        foreach ($pathes as $path) {
            $data[] = [
                'goods_id' => $goods_id,
                'path'     => $path,
            ];
        }
        $goods_gallery_model = M('GoodsGallery');
        if ($data && $goods_gallery_model->addAll($data) === false) {
            $this->error = '商品相册保存失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

    /**
     * 计算货号,如果手动填写了,就检查是否已经存在,否则,就自动生成一个类似于:
     * SN2016080200001的货号
     * @return boolean 货号重复返回false,否则true
     */
    private function _calcSn() {
        //自动生成
        //1.获取当天已经创建了多少个商品
        $goods_day_count_model = M('GoodsDayCount');
        $date                  = date('Ymd');
        $cond                  = [
            'day' => $date,
        ];
        //取出当天已经创建了多少个商品
        $count                 = $goods_day_count_model->where($cond)->getField('count');
        //今天已经有商品创建了
        if ($count) {
            $count++;
            $data = [
                'day'   => $date,
                'count' => $count,
            ];
            $goods_day_count_model->save($data);
        } else {
            $count = 1;
            $data  = [
                'day'   => $date,
                'count' => $count,
            ];
            $goods_day_count_model->add($data);
        }
        $this->data['sn'] = 'SN' . $date . str_pad($count, 5, '0', STR_PAD_LEFT);
        return true;
    }

    /**
     * 获取分页数据
     * @param array $cond 查询条件.
     * @return array
     */
    public function getPageResult(array $cond = array()) {
        //得到结果集行数
        $count        = $this->where($cond)->count();
        $size         = C('PAGE.SIZE');
        $page         = new \Think\Page($count, $size);
        $page->setConfig('theme', C('PAGE.THEME'));
        $page_html    = $page->show();
        //获取当前页数据
        $rows         = $this->where($cond)->page(I('get.p', 1), $size)->order('sort')->select();
        foreach ($rows as $key => $value) {
            $rows[$key]['is_best'] = $value['goods_status'] & 1 ? 1 : 0;
            $rows[$key]['is_new']  = $value['goods_status'] & 2 ? 1 : 0;
            $rows[$key]['is_hot']  = $value['goods_status'] & 4 ? 1 : 0;
        }
        return compact('page_html', 'rows');
    }

    /**
     * 获取商品信息,包括基本信息,详细描述和相册.
     * @param integer $id 商品id.
     * @return array
     */
    public function getGoodsInfo($id) {
        //获取基本信息
        $row          = $this->find($id);
        $goods_status = [];
        if ($row['goods_status'] & 1) {
            array_push($goods_status, 1);
        }
        if ($row['goods_status'] & 2) {
            array_push($goods_status, 2);
        }
        if ($row['goods_status'] & 4) {
            array_push($goods_status, 4);
        }
        $row['goods_status'] = json_encode($goods_status);

        //获取详细信息
        $goods_intro_model = M('GoodsIntro');
        $row['content']    = $goods_intro_model->getFieldByGoodsId($id, 'content');

        //TODO::相册的获取
        $goods_gallery_model = M('GoodsGallery');
        $row['galleries']    = $goods_gallery_model->where(['goods_id' => $id])->getField('id,id,path');
        return $row;
    }

    /**
     * 保存商品信息,包括基本信息,详细描述和相册.
     * @return boolean
     */
    public function saveGoods() {
        $this->startTrans();
        $goods_id = $this->data['id'];
        //1.保存基本信息
        if ($this->save() === false) {
            $this->rollback();
            return false;
        }
        //2.保存详细描述
        $data              = [
            'goods_id' => $goods_id,
            'content'  => I('post.content', '', false),
        ];
        $goods_intro_model = M('GoodsIntro');
        if ($goods_intro_model->save($data) === false) {
            $this->error = '商品详情保存失败';
            $this->rollback();
            return false;
        }
        //3.保存相册信息
        $pathes = I('post.path');
        $data   = [];
        foreach ($pathes as $path) {
            $data[] = [
                'goods_id' => $goods_id,
                'path'     => $path,
            ];
        }
        $goods_gallery_model = M('GoodsGallery');
        if ($data && $goods_gallery_model->addAll($data) === false) {
            $this->error = '商品相册保存失败';
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }

}
