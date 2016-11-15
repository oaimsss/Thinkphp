<?php

/**
 * @link http://blog.kunx.org/.
 * @copyright Copyright (c) 2016-11-12 
 * @license kunx-edu@qq.com.
 */

namespace Common\Behaviors;

/**
 * Description of CheckPermissionBehavior
 *
 * @author kunx <kunx-edu@qq.com>
 */
class CheckPermissionBehavior extends \Think\Behavior {

    public function run(&$param) {
        //执行逻辑
        //增加排除列表，login captcha
        $ignores = C('RBAC.IGNORE');
        $url     = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        if (in_array($url, $ignores)) {
            return true;
        }
        //检查用户是否登录
        if (!$admininfo = session('ADMIN_INFO')) {

            //尝试自动登录
            if (!$admininfo = D('Admin')->autoLogin()) {
                //没有登录跳转到登录页面
                $url = U('Admin/login');
                redirect($url);
            }
        }

        //已登录用户的忽略列表
        $user_ignores = C('RBAC.USER_IGNORE');
        if (in_array($url, $user_ignores)) {
            return true;
        }

        //检查RBAC权限
        //获取当前用户所拥有的的权限
        //SELECT   sp.id,path FROM  shop_admin_role AS ar   JOIN shop_role_permission AS rp     ON ar.`role_id` = rp.`role_id`   JOIN shop_permission AS sp   ON rp.`permission_id`=sp.`id`WHERE ar.`admin_id`=2 
//        $permissions = M('AdminRole')->alias('ar')->field('p.id,path')->join('__ROLE_PERMISSION__ as rp using(`role_id`)')->join('__PERMISSION__ as p ON rp.`permission_id`=p.`id`')->where(['ar.admin_id'=>$admininfo['id']])->select();
//        foreach($permissions as $permission){
//            if($url === $permission['path']){
//                return TRUE;
//            }
//        }
        //获取管理员的权限列表
        $permissions = session('ADMIN_PATH');
        if (in_array($url, $permissions)) {
            return true;
        } else {
            echo '<script type="text/javascript">alert("无权访问");history.back();</script>';
            exit;
        }
//        dump($permissions);
//        return true;
    }

}
