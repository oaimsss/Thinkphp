<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-5 
 * @license kunx-edu@qq.com.
 */

namespace Admin\Controller;

/**
 * Description of UploadController
 *
 * @author kunx <kunx-edu@qq.com>
 */
class UploadController extends \Think\Controller {

    //put your code here
    public function upload() {
        //收集数据
        $config   = C('UPLOAD_SETTING');
        $upload   = new \Think\Upload($config);
        //保存文件
        $fileinfo = $upload->upload();
        $fileinfo = array_pop($fileinfo);
        $data     = [];
        if (!$fileinfo) {
            $data = [
                'status' => false,
                'msg'    => $upload->getError(),
                'url'    => '',
            ];
        } else {
            if($upload->driver == 'Qiniu'){
                $url = $fileinfo['url'];
            }else{
                $url = C('BASE_URL') . $upload->rootPath . $fileinfo['savepath'] . $fileinfo['savename'];
            }
            $data = [
                'status' => true,
                'msg'    => '上传成功',
                'url'    => $url,
            ];
        }
        //返回结果
        $this->ajaxReturn($data);
    }

}
