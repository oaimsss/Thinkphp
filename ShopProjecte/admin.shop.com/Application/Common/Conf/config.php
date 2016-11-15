<?php

return array(
    'BASE_URL'          => 'http://admin.shop.com/',
    //'配置项'=>'配置值'
    'URL_MODEL'         => 2,
    'TMPL_PARSE_STRING' => [
        '__CSS__'       => '/Public/css',
        '__JS__'        => '/Public/js',
        '__IMG__'       => '/Public/images',
        '__UPLOADIFY__' => '/Public/ext/uploadify',
        '__LAYER__'     => '/Public/ext/layer',
        '__ZTREE__'     => '/Public/ext/ztree',
        '__UEDITOR__'   => '/Public/ext/ueditor',
    ],
    /* 数据库设置 */
    'DB_TYPE'           => 'mysql', // 数据库类型
    'DB_HOST'           => '127.0.0.1', // 服务器地址
    'DB_NAME'           => 'php0813_shop', // 数据库名
    'DB_USER'           => 'root', // 用户名
    'DB_PWD'            => '123456', // 密码
    'DB_PORT'           => '3306', // 端口
    'DB_PREFIX'         => 'shop_', // 数据库表前缀
    'DB_PARAMS'         => array(), // 数据库连接参数    
    'DB_DEBUG'          => TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'   => true, // 启用字段缓存
    'DB_CHARSET'        => 'utf8', // 数据库编码默认采用utf8
    'PAGE'           => [
        'SIZE'  => 20,
        'THEME' => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%',
    ],
    'COOKIE_PREFIX'  => 'admin_shop_com_',
    'UPLOAD_SETTING' => [
        'mimes'        => array('image/jpeg', 'image/png'), //允许上传的文件MiMe类型
        'maxSize'      => 0, //上传的文件大小限制 (0-不做限制)
        'exts'         => array('jpg', 'jpeg', 'jpe', 'png'), //允许上传的文件后缀
        'autoSub'      => true, //自动子目录保存文件
        'subName'      => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'     => './', //保存根路径
        'savePath'     => 'Uploads/', //保存路径
        'saveName'     => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'      => '', //文件保存后缀，空则使用原后缀
        'replace'      => false, //存在同名是否覆盖
        'hash'         => false, //是否生成hash编码
        'callback'     => false, //检测文件是否存在回调，如果存在返回文件信息数组
        'driver'       => 'Qiniu', // 文件上传驱动
        'driverConfig' => array(
            'secretKey' => 'KBYoPnqTbgX4a65rXNI9f-6_kCKwwnHMSnLOGLNk', //七牛服务器
            'accessKey' => 'qJHe4wo24q6X6AWSXsv-syl8PkhHjo6i5WXc-to5', //七牛用户
            'domain'    => 'og5tgt5wl.bkt.clouddn.com', //域名
            'bucket'    => 'tp0813', //空间名称
            'timeout'   => 30, //超时时间
        ), // 上传驱动配置
    ],
    //权限验证的忽略列表.
    'RABC'           => [
        'IGNORE'      => [
            'Admin/Admin/login',
            'Admin/Captcha/show',
        ],
        'USER_IGNORE' => [
            'Admin/Index/index',
            'Admin/Index/top',
            'Admin/Index/menu',
            'Admin/Index/main',
            'Admin/Admin/logout',
            'Admin/Editor/ueditor',
            'Admin/Upload/upload',
        ],
    ],
);
