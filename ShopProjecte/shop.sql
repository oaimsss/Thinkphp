CREATE DATABASE php0813_shop CHARSET utf8
;USE php0813_shop
;CREATE TABLE shop_brand (
id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`name` VARCHAR(50) NOT NULL COMMENT '品牌名称',
intro VARCHAR(255) NOT NULL DEFAULT '' COMMENT '简介',
logo VARCHAR(255) NOT NULL DEFAULT ''  COMMENT '品牌标识',
sort INT UNSIGNED NOT NULL DEFAULT 20 COMMENT '排序，数字越小越靠前',
`status` TINYINT NOT NULL DEFAULT 1 COMMENT '1正常 0隐藏 -1删除'
)CHARSET utf8 ENGINE INNODB


;CREATE TABLE shop_article_category(
        `id` TINYINT UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '名称',
        `intro` TEXT COMMENT '简介@textarea',
        `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态@radio|1=是&0=否',
        `sort` TINYINT  NOT NULL DEFAULT 20 COMMENT '排序',
        `is_help` TINYINT NOT NULL DEFAULT 1 COMMENT '是否是帮助相关的分类'
)ENGINE=MYISAM COMMENT '文章分类'



;CREATE TABLE shop_article(
        `id` INT UNSIGNED  PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '名称',
        `article_category_id` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章分类',
        `intro` TEXT COMMENT '简介@textarea',
        `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态@radio|1=是&0=否',
        `sort` TINYINT  NOT NULL DEFAULT 20 COMMENT '排序',
        `inputtime` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '录入时间',
        KEY(article_category_id)
)ENGINE=MYISAM COMMENT '文章'



;CREATE TABLE shop_article_content(
      `article_id` INT UNSIGNED  PRIMARY KEY,
      `content` TEXT COMMENT '文章内容'
)ENGINE=MYISAM COMMENT '文章内容'


;CREATE TABLE shop_goods_category (
	id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL COMMENT '分类名称',
	parent_id INT UNSIGNED NOT NULL COMMENT '父级分类',
	`lft` INT UNSIGNED NOT NULL COMMENT '左节点',
	`rght` INT UNSIGNED NOT NULL COMMENT '右节点',
	`level` TINYINT UNSIGNED NOT NULL COMMENT '层级',
	intro VARCHAR(255) COMMENT '描述'
)CHARSET utf8 ENGINE INNODB 

;SELECT parent_id, `left`, `right`, LEVEL FROM shop_goods_category WHERE id = 0


#商品基本信息表
;CREATE TABLE shop_goods (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR (50) NOT NULL DEFAULT '' COMMENT '名称',
  `sn` CHAR (15) NOT NULL DEFAULT '' COMMENT '货号',  # SN20150825000000000id
  `logo` VARCHAR (150) NOT NULL DEFAULT '' COMMENT '商品LOGO',
  `goods_category_id` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品分类',
  `brand_id` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '品牌',
  `supplier_id` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '供货商',
  `market_price` DECIMAL (10, 2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市场价格',
  `shop_price` DECIMAL (10, 2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '本店价格',
  `stock` INT NOT NULL DEFAULT 0 COMMENT '库存',
  `goods_status` INT NOT NULL DEFAULT 0 COMMENT '商品状态',  #精品 新品 热销  使用二进制表示
  `is_on_sale` TINYINT NOT NULL DEFAULT 1 COMMENT '是否上架',  #1表示上架  0:不上架
  `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态@radio|1=是&0=否',
  `sort` TINYINT NOT NULL DEFAULT 20 COMMENT '排序',
  `inputtime` INT NOT NULL DEFAULT 0 COMMENT '录入时间',
  INDEX (`goods_category_id`),
  INDEX (`brand_id`),
  INDEX (`supplier_id`)
) ENGINE = INNODB COMMENT '商品'

#商品描述表
;CREATE TABLE shop_goods_intro (
  `goods_id` BIGINT PRIMARY KEY COMMENT '商品ID',
  `content` TEXT COMMENT '商品描述'
) ENGINE = INNODB COMMENT '商品描述'

# 保存每天创建了多少个商品
;CREATE TABLE shop_goods_day_count(
`day` DATE COMMENT '日期' PRIMARY KEY,
`count` INT UNSIGNED COMMENT '商品数',
KEY(`day`)
)ENGINE=INNODB CHARSET utf8


# 商品相册
;CREATE TABLE `shop_goods_gallery` (
   `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_id` BIGINT(20) DEFAULT NULL COMMENT '商品ID',
  `path` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商品图片地址',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COMMENT='商品相册'


#admin(管理员表)
;CREATE TABLE shop_admin (
  `id` TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR (50) NOT NULL DEFAULT '' COMMENT '用户名' UNIQUE,
  `password` CHAR(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` CHAR(6) NOT NULL DEFAULT '' COMMENT '盐',
  `email` VARCHAR (30) NOT NULL DEFAULT '' COMMENT '邮箱' UNIQUE,
  `add_time` INT NOT NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_time` INT NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `last_login_ip` BIGINT NOT NULL DEFAULT 0 COMMENT '最后登录IP'
) ENGINE = INNODB COMMENT '管理员'

# 添加令牌字段用于自动登录
;ALTER TABLE shop_admin ADD token CHAR(32) DEFAULT '' COMMENT '自动登录令牌'


#角色表
;CREATE TABLE shop_role(
id INT UNSIGNED PRIMARY	KEY AUTO_INCREMENT,
`name` VARCHAR(20) NOT NULL COMMENT '角色名称',
`intro` VARCHAR(255) COMMENT '描述',
sort INT UNSIGNED NOT NULL DEFAULT 10 COMMENT '排序，越小越靠前'
 
)CHARSET utf8 ENGINE INNODB

#权限表
;CREATE TABLE shop_permission(
id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
`name` VARCHAR(20) NOT NULL COMMENT '权限名称',
`path` VARCHAR(50) NOT NULL COMMENT '操作路径：  模块/控制器/操作方法',
parent_id INT UNSIGNED NOT NULL COMMENT '父级权限',
lft INT UNSIGNED NOT NULL COMMENT '左节点',
rght INT UNSIGNED NOT NULL COMMENT '右节点',
`level` INT UNSIGNED NOT NULL COMMENT '层级',
intro VARCHAR(255) COMMENT '描述'
)CHARSET utf8 ENGINE INNODB

#角色-权限 关联表
;CREATE TABLE shop_role_permission(
id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
role_id INT UNSIGNED NOT NULL COMMENT '角色',
permission_id INT UNSIGNED NOT NULL COMMENT '权限',
KEY(role_id),
KEY(permission_id)
)CHARSET utf8 ENGINE INNODB

#管理员-角色  关联表
;CREATE TABLE shop_admin_role(
id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
admin_id INT UNSIGNED NOT NULL COMMENT '管理员',
role_id INT UNSIGNED NOT NULL COMMENT '角色',
KEY(admin_id),
KEY(role_id)

)CHARSET utf8 ENGINE INNODB

#通过角色获取权限
;
SELECT 
  p.id,path
FROM
  shop_admin_role AS ar 
  JOIN shop_role_permission AS rp 
    USING(role_id) 
  JOIN shop_permission AS p 
  ON rp.`permission_id`=p.`id`
WHERE ar.`admin_id`=2  
  