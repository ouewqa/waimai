/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.6.16 : Database - waimai
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`waimai` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `waimai`;

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `mobile` char(11) NOT NULL COMMENT '手机号码',
  `email` varchar(45) NOT NULL COMMENT '邮件',
  `mobile_is_verify` enum('Y','N') DEFAULT 'N',
  `email_is_verify` enum('Y','N','W') DEFAULT 'N' COMMENT 'W waiting',
  `sms_number` smallint(5) unsigned DEFAULT '10',
  `regist_ip` char(15) DEFAULT NULL,
  `regist_time` int(11) unsigned DEFAULT NULL,
  `last_login_time` int(11) unsigned DEFAULT NULL,
  `last_login_ip` char(15) DEFAULT NULL,
  `login_times` int(11) unsigned DEFAULT NULL,
  `salt` char(6) NOT NULL COMMENT '密码salt',
  `status` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后�';

/*Data for the table `admin` */

/*Table structure for table `alipay_order` */

DROP TABLE IF EXISTS `alipay_order`;

CREATE TABLE `alipay_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `seller_email` varchar(255) DEFAULT NULL COMMENT '买家',
  `trade_no` varchar(45) NOT NULL COMMENT '订单号',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `quantity` int(11) unsigned DEFAULT '1',
  `money` int(11) unsigned NOT NULL,
  `subject` varchar(45) DEFAULT NULL COMMENT '交易标题',
  `description` text COMMENT '交易描述',
  `product` varchar(45) DEFAULT NULL COMMENT '产品号',
  `url` varchar(255) DEFAULT NULL COMMENT '产品网址',
  `status` enum('Y','N','W') DEFAULT 'W' COMMENT 'Y 支付成功　N 支付失败　W 等待支付',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trade_no_UNIQUE` (`trade_no`),
  KEY `fk_alipay_order_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_alipay_order_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付订单';

/*Data for the table `alipay_order` */

/*Table structure for table `area` */

DROP TABLE IF EXISTS `area`;

CREATE TABLE `area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `path` varchar(200) DEFAULT NULL,
  `level` smallint(3) unsigned DEFAULT NULL COMMENT '节点层次',
  `status` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `index3` (`status`),
  KEY `index4` (`level`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3268 DEFAULT CHARSET=utf8 COMMENT='区域分类（省市区）';

/*Data for the table `area` */

/*Table structure for table `authassignment` */

DROP TABLE IF EXISTS `authassignment`;

CREATE TABLE `authassignment` (
  `itemname` varchar(45) NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `child` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `authassignment` */

/*Table structure for table `authitem` */

DROP TABLE IF EXISTS `authitem`;

CREATE TABLE `authitem` (
  `name` varchar(45) NOT NULL,
  `type` int(11) unsigned NOT NULL DEFAULT '2' COMMENT '默认为角色 2',
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `authitem` */

/*Table structure for table `authitemchild` */

DROP TABLE IF EXISTS `authitemchild`;

CREATE TABLE `authitemchild` (
  `parent` varchar(45) NOT NULL,
  `child` varchar(45) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `authitemchild` */

/*Table structure for table `comment` */

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `type` enum('T','W','C','P','WI','V') DEFAULT NULL COMMENT '�',
  `relation_id` int(11) unsigned DEFAULT NULL COMMENT '关键ID',
  PRIMARY KEY (`id`),
  KEY `index2` (`type`,`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

/*Data for the table `comment` */

/*Table structure for table `logs` */

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '用户',
  `type` smallint(5) unsigned DEFAULT NULL COMMENT '日志类型',
  `operation` text COMMENT '具体操作SQL',
  `mark` varchar(1024) DEFAULT NULL COMMENT '备注',
  `dateline` int(11) unsigned DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `fk_logs_admin1` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志记录';

/*Data for the table `logs` */

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_group_id` int(11) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `type` enum('js','link') DEFAULT 'link' COMMENT '�',
  `value` varchar(1024) DEFAULT NULL COMMENT '链接或脚本内容',
  `status` enum('Y','N') DEFAULT 'Y' COMMENT '�',
  `display` enum('Y','N') DEFAULT 'N' COMMENT '是否为登陆显示',
  PRIMARY KEY (`id`),
  KEY `fk_menu_menu_group1_idx` (`menu_group_id`),
  CONSTRAINT `fk_menu_menu_group1` FOREIGN KEY (`menu_group_id`) REFERENCES `menu_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

/*Table structure for table `menu_group` */

DROP TABLE IF EXISTS `menu_group`;

CREATE TABLE `menu_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `sign` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `menu_group` */

/*Table structure for table `module` */

DROP TABLE IF EXISTS `module`;

CREATE TABLE `module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT '模块名称',
  `sign` varchar(45) DEFAULT NULL COMMENT '模块标识',
  `ob` tinyint(3) unsigned DEFAULT '255',
  PRIMARY KEY (`id`),
  KEY `sign` (`sign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `module` */

/*Table structure for table `page` */

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `sign` varchar(45) DEFAULT NULL COMMENT '标识',
  `template` varchar(45) DEFAULT NULL COMMENT '模块名称',
  `ob` tinyint(3) unsigned DEFAULT '255' COMMENT '排序值',
  `dateline` int(11) unsigned DEFAULT NULL,
  `type` enum('T','B') DEFAULT NULL COMMENT '�',
  PRIMARY KEY (`id`),
  KEY `sign` (`sign`),
  KEY `ob` (`ob`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页管理';

/*Data for the table `page` */

/*Table structure for table `payment_method` */

DROP TABLE IF EXISTS `payment_method`;

CREATE TABLE `payment_method` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `status` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付方式';

/*Data for the table `payment_method` */

/*Table structure for table `position` */

DROP TABLE IF EXISTS `position`;

CREATE TABLE `position` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(45) DEFAULT NULL COMMENT '所属模块',
  `sign` varchar(45) DEFAULT NULL COMMENT '英文标识',
  `name` varchar(45) DEFAULT NULL COMMENT '推荐位名称',
  `maxnum` smallint(5) DEFAULT NULL,
  `ob` tinyint(3) unsigned DEFAULT '255' COMMENT '排序，越小越靠前',
  `image` varchar(255) DEFAULT NULL COMMENT '图片，演示推荐位所在的位置',
  `template` varchar(45) DEFAULT NULL COMMENT '推荐位模板',
  `system` enum('Y','N') DEFAULT 'N' COMMENT '是否为系统内部用',
  PRIMARY KEY (`id`),
  KEY `index2` (`module`,`sign`,`ob`),
  KEY `system` (`system`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐位';

/*Data for the table `position` */

/*Table structure for table `position_data` */

DROP TABLE IF EXISTS `position_data`;

CREATE TABLE `position_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(11) unsigned NOT NULL,
  `text` varchar(45) DEFAULT NULL COMMENT '文字',
  `description` varchar(1024) DEFAULT NULL COMMENT '描述',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `extra` text COMMENT '额外数据',
  `ob` smallint(5) DEFAULT '100' COMMENT '排序值　越小越靠前',
  `sign` int(11) unsigned DEFAULT NULL COMMENT '标识（判断是否已经存在标识）',
  `new_window` tinyint(1) unsigned DEFAULT '1' COMMENT '弹新窗',
  `status` enum('Y','N') DEFAULT 'Y',
  `expiration` int(11) unsigned DEFAULT '0',
  `dateline` int(11) unsigned DEFAULT NULL COMMENT '提交时间',
  `type` varchar(45) DEFAULT NULL COMMENT 'A Article',
  PRIMARY KEY (`id`),
  KEY `fk_position_data_position1_idx` (`position_id`),
  KEY `ob` (`ob`),
  KEY `status` (`status`),
  CONSTRAINT `fk_position_data_position1` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐位数据';

/*Data for the table `position_data` */

/*Table structure for table `room` */

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned zerofill NOT NULL,
  `name` varchar(45) NOT NULL,
  `type` enum('PRI','PUB') DEFAULT 'PUB' COMMENT 'PRI �',
  `number_of_people` tinyint(3) unsigned DEFAULT '1' COMMENT '容纳人数',
  `images` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_room_shop1_idx` (`shop_id`),
  CONSTRAINT `fk_room_shop1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `room` */

/*Table structure for table `room_order` */

DROP TABLE IF EXISTS `room_order`;

CREATE TABLE `room_order` (
  `weixin_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  `order_time` int(11) unsigned DEFAULT NULL COMMENT '预订时间',
  `number_of_people` tinyint(3) unsigned DEFAULT '1' COMMENT '人数',
  `dateline` int(11) unsigned DEFAULT NULL,
  `status` enum('Y','N','F') DEFAULT 'N' COMMENT '�',
  PRIMARY KEY (`weixin_id`,`room_id`),
  KEY `fk_weixin_has_room_room1_idx` (`room_id`),
  KEY `fk_weixin_has_room_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_weixin_has_room_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_weixin_has_room_room1` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `room_order` */

/*Table structure for table `sentence` */

DROP TABLE IF EXISTS `sentence`;

CREATE TABLE `sentence` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word_meaning_id` int(11) unsigned NOT NULL,
  `weixin_id` int(11) unsigned NOT NULL,
  `sentence` varchar(255) NOT NULL,
  `meaning` varchar(255) NOT NULL,
  `hiragara` varchar(1024) DEFAULT NULL,
  `dateline` int(11) unsigned DEFAULT '0',
  `tts_id` int(11) DEFAULT NULL COMMENT 'TTS id',
  PRIMARY KEY (`id`),
  KEY `fk_sentence_weixin1_idx` (`weixin_id`),
  KEY `dateline` (`dateline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='例句';

/*Data for the table `sentence` */

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL COMMENT '类型',
  `name` varchar(45) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站配置';

/*Data for the table `setting` */

/*Table structure for table `share_log` */

DROP TABLE IF EXISTS `share_log`;

CREATE TABLE `share_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `count_view` int(11) unsigned DEFAULT '0' COMMENT '统计被查看的人数',
  `sign` char(32) NOT NULL COMMENT '对URL进行MD5,方便搜索',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shareStatistics_weixin1_idx` (`weixin_id`),
  KEY `sign` (`sign`(6)),
  CONSTRAINT `fk_shareStatistics_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分享记录';

/*Data for the table `share_log` */

/*Table structure for table `shop` */

DROP TABLE IF EXISTS `shop`;

CREATE TABLE `shop` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `sn` tinyint(3) unsigned DEFAULT '1' COMMENT '门店编号',
  `description` text,
  `province` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `district` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `mobile` char(11) NOT NULL,
  `map_point` varchar(128) DEFAULT NULL COMMENT '地理位置',
  `opening_time_start` char(5) DEFAULT NULL,
  `opening_time_end` char(5) DEFAULT NULL,
  `minimum_charge` tinyint(3) unsigned DEFAULT '0' COMMENT '起送价',
  `express_fee` tinyint(3) unsigned DEFAULT '0' COMMENT '配送费',
  `status` enum('Y','N') DEFAULT 'Y',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shop_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_shop_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shop` */

/*Table structure for table `shop_dish` */

DROP TABLE IF EXISTS `shop_dish`;

CREATE TABLE `shop_dish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_dish_category_id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL COMMENT '名称',
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(5,2) DEFAULT NULL COMMENT '价格',
  `discount` varchar(45) DEFAULT NULL COMMENT '折扣',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_shop_dish_shop_dish_category1_idx` (`shop_dish_category_id`),
  CONSTRAINT `fk_shop_dish_shop_dish_category1` FOREIGN KEY (`shop_dish_category_id`) REFERENCES `shop_dish_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shop_dish` */

/*Table structure for table `shop_dish_album` */

DROP TABLE IF EXISTS `shop_dish_album`;

CREATE TABLE `shop_dish_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_dish_id` int(10) unsigned NOT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `description` varchar(1024) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `fk_shop_dish_album_shop_dish1_idx` (`shop_dish_id`),
  CONSTRAINT `fk_shop_dish_album_shop_dish1` FOREIGN KEY (`shop_dish_id`) REFERENCES `shop_dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜品相册';

/*Data for the table `shop_dish_album` */

/*Table structure for table `shop_dish_category` */

DROP TABLE IF EXISTS `shop_dish_category`;

CREATE TABLE `shop_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned zerofill NOT NULL,
  `name` varchar(45) NOT NULL,
  `ob` tinyint(3) DEFAULT '0' COMMENT 'order by ',
  PRIMARY KEY (`id`),
  KEY `fk_dish_category_shop1_idx` (`shop_id`),
  CONSTRAINT `fk_dish_category_shop1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shop_dish_category` */

/*Table structure for table `shop_order` */

DROP TABLE IF EXISTS `shop_order`;

CREATE TABLE `shop_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `delivery_time` varchar(45) NOT NULL DEFAULT '',
  `delivery_address` varchar(255) NOT NULL,
  `comment` varchar(255) DEFAULT NULL COMMENT '订单留言',
  `payment_method_id` int(11) unsigned NOT NULL,
  `status` enum('U','P','D','C') DEFAULT 'U' COMMENT 'U unpaid 未',
  PRIMARY KEY (`id`),
  KEY `fk_shop_order_weixin1_idx` (`weixin_id`),
  KEY `fk_shop_order_payment_method1_idx` (`payment_method_id`),
  CONSTRAINT `fk_shop_order_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_shop_order_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';

/*Data for the table `shop_order` */

/*Table structure for table `shop_order_item` */

DROP TABLE IF EXISTS `shop_order_item`;

CREATE TABLE `shop_order_item` (
  `shop_order_id` int(11) unsigned NOT NULL,
  `shop_dish_id` int(11) unsigned NOT NULL,
  `number` tinyint(3) unsigned DEFAULT '1',
  PRIMARY KEY (`shop_order_id`,`shop_dish_id`),
  KEY `fk_shop_order_has_shop_dish_shop_dish1_idx` (`shop_dish_id`),
  KEY `fk_shop_order_has_shop_dish_shop_order1_idx` (`shop_order_id`),
  CONSTRAINT `fk_shop_order_has_shop_dish_shop_order1` FOREIGN KEY (`shop_order_id`) REFERENCES `shop_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_shop_order_has_shop_dish_shop_dish1` FOREIGN KEY (`shop_dish_id`) REFERENCES `shop_dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shop_order_item` */

/*Table structure for table `shop_payment_config` */

DROP TABLE IF EXISTS `shop_payment_config`;

CREATE TABLE `shop_payment_config` (
  `shop_id` int(11) unsigned zerofill NOT NULL,
  `payment_method_id` int(11) unsigned NOT NULL,
  `key` varchar(45) DEFAULT NULL,
  `value` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`shop_id`,`payment_method_id`),
  KEY `fk_shop_has_payment_method_payment_method1_idx` (`payment_method_id`),
  KEY `fk_shop_has_payment_method_shop1_idx` (`shop_id`),
  CONSTRAINT `fk_shop_has_payment_method_shop1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_shop_has_payment_method_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付配置';

/*Data for the table `shop_payment_config` */

/*Table structure for table `shop_products` */

DROP TABLE IF EXISTS `shop_products`;

CREATE TABLE `shop_products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` text,
  `price` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `specifications` text,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `shop_products` */

/*Table structure for table `shop_shipping_method` */

DROP TABLE IF EXISTS `shop_shipping_method`;

CREATE TABLE `shop_shipping_method` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `tax_id` int(11) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `shop_shipping_method` */

/*Table structure for table `shopping_cart` */

DROP TABLE IF EXISTS `shopping_cart`;

CREATE TABLE `shopping_cart` (
  `weixin_id` int(11) unsigned NOT NULL,
  `shop_dish_id` int(11) unsigned NOT NULL,
  `number` tinyint(3) unsigned DEFAULT '1',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`weixin_id`,`shop_dish_id`),
  KEY `fk_weixin_has_shop_dish_shop_dish1_idx` (`shop_dish_id`),
  KEY `fk_weixin_has_shop_dish_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_weixin_has_shop_dish_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_weixin_has_shop_dish_shop_dish1` FOREIGN KEY (`shop_dish_id`) REFERENCES `shop_dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shopping_cart` */

/*Table structure for table `sms_log` */

DROP TABLE IF EXISTS `sms_log`;

CREATE TABLE `sms_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `shop_id` int(11) unsigned zerofill NOT NULL,
  `type` enum('O','V','R') NOT NULL COMMENT 'O为�',
  `mobile` char(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `status` enum('Y','N','F') DEFAULT 'N' COMMENT 'Y　�',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sms_log_weixin_account1_idx` (`weixin_account_id`),
  KEY `fk_sms_log_shop1_idx` (`shop_id`),
  CONSTRAINT `fk_sms_log_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sms_log_shop1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sms_log` */

/*Table structure for table `verification_code` */

DROP TABLE IF EXISTS `verification_code`;

CREATE TABLE `verification_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `type` varchar(45) NOT NULL,
  `target` varchar(45) NOT NULL,
  `code` char(4) NOT NULL,
  `dateline` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  `status` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `fk_verification_code_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_verification_code_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='验证码';

/*Data for the table `verification_code` */

/*Table structure for table `weixin` */

DROP TABLE IF EXISTS `weixin`;

CREATE TABLE `weixin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `open_id` varchar(45) NOT NULL,
  `weixin_group_id` int(11) DEFAULT '0' COMMENT '微信群组',
  `username` varchar(45) DEFAULT NULL COMMENT '用户名',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `nickname` varchar(45) DEFAULT NULL COMMENT '用户昵称',
  `sex` tinyint(1) unsigned DEFAULT '0' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `credit` int(11) unsigned DEFAULT '0' COMMENT '积分',
  `birthday` int(11) DEFAULT NULL COMMENT '出生年月',
  `realname` varchar(45) DEFAULT NULL COMMENT '真实名字',
  `qq` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `province` varchar(45) DEFAULT NULL COMMENT '省',
  `city` varchar(45) DEFAULT NULL COMMENT '市',
  `district` varchar(45) NOT NULL COMMENT '区',
  `address` varchar(255) NOT NULL COMMENT '具体地址',
  `headimgurl` varchar(255) DEFAULT NULL COMMENT '头像',
  `qrcode_ticket` varchar(255) DEFAULT NULL COMMENT '定制二维码地址',
  `qrcode_create_time` int(11) unsigned DEFAULT NULL COMMENT '二维码生成时间',
  `last_update_time` int(11) unsigned DEFAULT NULL COMMENT '最后同时微信API时间',
  `last_request_time` int(11) unsigned DEFAULT NULL COMMENT '最后请求时间',
  `last_response_time` int(11) unsigned DEFAULT NULL COMMENT '最后系统响应时间，由用户触发',
  `last_location_time` int(11) DEFAULT NULL COMMENT '最后一次地理位置时间',
  `last_push_time` int(11) DEFAULT NULL COMMENT '最后推送时间，由系统触发',
  `status` enum('Y','N') DEFAULT 'Y',
  `updatetime` int(11) DEFAULT NULL COMMENT '用户最后活动时间',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `open_id_UNIQUE` (`open_id`),
  KEY `fk_weixin_weixin_account1_idx` (`weixin_account_id`),
  KEY `status` (`status`,`dateline`),
  CONSTRAINT `fk_weixin_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';

/*Data for the table `weixin` */

/*Table structure for table `weixin_account` */

DROP TABLE IF EXISTS `weixin_account`;

CREATE TABLE `weixin_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL,
  `name` varchar(45) NOT NULL COMMENT '名称',
  `source` varchar(45) NOT NULL COMMENT '原始号',
  `type` enum('D','F') NOT NULL DEFAULT 'D' COMMENT '类型D 订阅号F 服务号',
  `welcome_message` varchar(1024) DEFAULT NULL COMMENT '欢迎消息',
  `appid` char(18) DEFAULT NULL,
  `appsecret` char(32) DEFAULT NULL,
  `token` varchar(45) DEFAULT NULL COMMENT '识别Token，用户自定义',
  `access_token` varchar(512) DEFAULT NULL COMMENT '访问token',
  `access_token_expire_time` int(11) unsigned DEFAULT NULL COMMENT '访问token过期时间',
  `baidu_ak` varchar(45) DEFAULT NULL COMMENT '百度AK',
  `advanced_interface` enum('Y','N') DEFAULT 'N' COMMENT '拥有高级接口',
  `default` enum('Y','N') DEFAULT 'N' COMMENT '默认账号',
  `debug` enum('Y','N') DEFAULT 'Y' COMMENT '是否开启调试',
  `status` enum('Y','N') DEFAULT 'Y' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `source` (`source`),
  KEY `fk_weixin_account_admin1_idx` (`admin_id`),
  KEY `status` (`status`,`default`,`admin_id`),
  CONSTRAINT `fk_weixin_account_admin1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统后台微信账号';

/*Data for the table `weixin_account` */

/*Table structure for table `weixin_activation_code` */

DROP TABLE IF EXISTS `weixin_activation_code`;

CREATE TABLE `weixin_activation_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `code` char(32) DEFAULT NULL COMMENT '激活码',
  `expire` int(11) unsigned DEFAULT NULL COMMENT '过期时间',
  `creator` varchar(45) DEFAULT NULL COMMENT '创建者',
  `user` varchar(45) DEFAULT NULL COMMENT '使用者',
  `status` enum('Y','N') DEFAULT 'Y' COMMENT 'Y 未使用',
  `dateline` varchar(45) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_activation_code_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_weixin_activation_code_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='激活码';

/*Data for the table `weixin_activation_code` */

/*Table structure for table `weixin_announcement` */

DROP TABLE IF EXISTS `weixin_announcement`;

CREATE TABLE `weixin_announcement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `title` varchar(45) DEFAULT NULL COMMENT '公告标题',
  `content` varchar(1024) NOT NULL COMMENT '公告内容',
  `expire` int(11) unsigned DEFAULT NULL COMMENT '过期时间',
  `redirect` varchar(255) DEFAULT NULL,
  `view_count` int(11) unsigned DEFAULT '0',
  `jp_level` varchar(45) DEFAULT NULL,
  `area` varchar(1024) DEFAULT NULL,
  `identity` varchar(45) DEFAULT NULL,
  `status` enum('Y','N') DEFAULT 'Y' COMMENT '公告状态',
  `dateline` int(11) unsigned DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_announcement_weixin_account2_idx` (`weixin_account_id`),
  KEY `status` (`status`,`dateline`,`expire`),
  CONSTRAINT `fk_weixin_announcement_weixin_account2` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `weixin_announcement` */

/*Table structure for table `weixin_attachment` */

DROP TABLE IF EXISTS `weixin_attachment`;

CREATE TABLE `weixin_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) DEFAULT NULL,
  `dateline` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `weixin_account_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_attachment_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_weixin_attachment_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信资源表';

/*Data for the table `weixin_attachment` */

/*Table structure for table `weixin_command` */

DROP TABLE IF EXISTS `weixin_command`;

CREATE TABLE `weixin_command` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `source` varchar(45) DEFAULT NULL COMMENT '来源',
  `command` varchar(45) DEFAULT NULL COMMENT '命令',
  `match` enum('precise','fuzzy','regular') DEFAULT 'precise' COMMENT '匹�',
  `description` varchar(1024) DEFAULT NULL COMMENT '指令描述',
  `type` enum('flow','relation','function','event','interface') DEFAULT NULL COMMENT 'flow �',
  `value` varchar(255) DEFAULT NULL COMMENT '关联type的值',
  `status` enum('Y','N') DEFAULT NULL COMMENT '状态',
  `ob` int(11) unsigned DEFAULT '0' COMMENT '优',
  `cost` int(11) unsigned DEFAULT '0' COMMENT '消费金币',
  PRIMARY KEY (`id`),
  KEY `command` (`command`),
  KEY `fk_weixin_command_weixin_account1_idx` (`weixin_account_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_weixin_command_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信指令';

/*Data for the table `weixin_command` */

/*Table structure for table `weixin_command_log` */

DROP TABLE IF EXISTS `weixin_command_log`;

CREATE TABLE `weixin_command_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_command_id` int(11) unsigned NOT NULL,
  `open_id` varchar(45) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_command_log_weixin_command1_idx` (`weixin_command_id`),
  CONSTRAINT `fk_weixin_command_log_weixin_command1` FOREIGN KEY (`weixin_command_id`) REFERENCES `weixin_command` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信指令记录（用来统计指令的使用频率，进而调整优先级）';

/*Data for the table `weixin_command_log` */

/*Table structure for table `weixin_credit_log` */

DROP TABLE IF EXISTS `weixin_credit_log`;

CREATE TABLE `weixin_credit_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `credit` int(11) DEFAULT NULL,
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_credit_log_weixin1_idx` (`weixin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分记录表';

/*Data for the table `weixin_credit_log` */

/*Table structure for table `weixin_feedback` */

DROP TABLE IF EXISTS `weixin_feedback`;

CREATE TABLE `weixin_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `weixin_account` varchar(45) DEFAULT NULL COMMENT '用户个人微信',
  `email` varchar(45) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `feedback` varchar(1024) NOT NULL COMMENT '反馈内容',
  `reply` varchar(1024) DEFAULT NULL COMMENT '站长回复',
  `dateline` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `status` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_feedback_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_weixin_feedback_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈';

/*Data for the table `weixin_feedback` */

/*Table structure for table `weixin_group` */

DROP TABLE IF EXISTS `weixin_group`;

CREATE TABLE `weixin_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `group_id` int(11) DEFAULT NULL COMMENT '微信分组ID',
  `name` varchar(45) DEFAULT NULL COMMENT '组名',
  `ob` tinyint(3) unsigned DEFAULT '255',
  `member_count` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_group_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_weixin_group_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信群组';

/*Data for the table `weixin_group` */

/*Table structure for table `weixin_help` */

DROP TABLE IF EXISTS `weixin_help`;

CREATE TABLE `weixin_help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `title` varchar(45) DEFAULT NULL COMMENT '公告标题',
  `content` varchar(1024) NOT NULL COMMENT '公告内容',
  `status` enum('Y','N') DEFAULT 'Y' COMMENT '公告状态',
  `ob` tinyint(3) unsigned DEFAULT '0',
  `view_count` int(11) unsigned DEFAULT '0',
  `redirect` varchar(255) DEFAULT NULL,
  `dateline` int(11) unsigned DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_announcement_weixin_account2_idx` (`weixin_account_id`),
  KEY `status` (`status`,`dateline`),
  CONSTRAINT `fk_weixin_announcement_weixin_account20` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信使用帮助';

/*Data for the table `weixin_help` */

/*Table structure for table `weixin_location` */

DROP TABLE IF EXISTS `weixin_location`;

CREATE TABLE `weixin_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT '具体地址',
  `latitude` varchar(15) DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(15) DEFAULT NULL COMMENT '经度',
  `precision` varchar(15) DEFAULT NULL COMMENT '精度',
  `province` varchar(45) DEFAULT NULL COMMENT '省份',
  `city` varchar(45) DEFAULT NULL COMMENT '城市',
  `district` varchar(45) DEFAULT NULL COMMENT '区',
  `street` varchar(45) DEFAULT NULL,
  `street_number` varchar(45) DEFAULT NULL,
  `query_address` varchar(45) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `weixin_id_UNIQUE` (`weixin_id`),
  KEY `fk_weixin_location_weixin_account1_idx` (`weixin_account_id`),
  KEY `city` (`city`),
  KEY `fk_weixin_location_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_weixin_location_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信地理位置';

/*Data for the table `weixin_location` */

/*Table structure for table `weixin_log` */

DROP TABLE IF EXISTS `weixin_log`;

CREATE TABLE `weixin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `weixin_id` int(11) unsigned NOT NULL,
  `type` enum('notice','fatal','timeout') DEFAULT 'notice' COMMENT '日�',
  `content` varchar(1024) DEFAULT NULL COMMENT '备注',
  `execute_time` float unsigned DEFAULT NULL COMMENT '执行时间',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_log_weixin1_idx` (`weixin_id`),
  KEY `fk_weixin_log_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_weixin_log_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信日志';

/*Data for the table `weixin_log` */

/*Table structure for table `weixin_media` */

DROP TABLE IF EXISTS `weixin_media`;

CREATE TABLE `weixin_media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `type` enum('image','voice','video','mp3') NOT NULL,
  `media_id` char(64) DEFAULT NULL,
  `recognition` text COMMENT '语音识别内容',
  `path` varchar(255) DEFAULT NULL,
  `mp3` varchar(255) DEFAULT NULL,
  `dateline` int(11) unsigned DEFAULT '0',
  `expire` int(11) unsigned DEFAULT '0',
  `status` enum('Y','N') DEFAULT 'N',
  `count_use` int(11) unsigned DEFAULT '0' COMMENT '使用次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_id_UNIQUE` (`media_id`),
  KEY `fk_weixin_media_weixin1_idx` (`weixin_id`),
  CONSTRAINT `fk_weixin_media_weixin1` FOREIGN KEY (`weixin_id`) REFERENCES `weixin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='多媒体';

/*Data for the table `weixin_media` */

/*Table structure for table `weixin_menu` */

DROP TABLE IF EXISTS `weixin_menu`;

CREATE TABLE `weixin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) unsigned DEFAULT '0' COMMENT '父ID',
  `path` varchar(45) DEFAULT NULL,
  `name` varchar(21) DEFAULT NULL,
  `key` varchar(45) DEFAULT NULL COMMENT '点击事件中标识',
  `type` enum('view','click','menu') DEFAULT 'click' COMMENT 'view 跳�',
  `url` varchar(255) DEFAULT NULL COMMENT '最长255',
  `ob` tinyint(3) unsigned DEFAULT '255',
  `status` enum('Y','N') DEFAULT 'Y',
  `weixin_account_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_menu_weixin_account1_idx` (`weixin_account_id`),
  CONSTRAINT `fk_weixin_menu_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信菜单';

/*Data for the table `weixin_menu` */

/*Table structure for table `weixin_message` */

DROP TABLE IF EXISTS `weixin_message`;

CREATE TABLE `weixin_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weixin_account_id` int(11) unsigned NOT NULL,
  `weixin_id` int(11) unsigned NOT NULL,
  `message` varchar(2048) DEFAULT NULL COMMENT '内容',
  `flag` tinyint(1) unsigned DEFAULT NULL COMMENT '星标',
  `type` enum('text','news','image','location','link','voice','event','music') DEFAULT 'text',
  `dateline` int(11) unsigned DEFAULT NULL COMMENT '时间',
  `status` enum('Y','N') DEFAULT 'N' COMMENT '状态',
  `weixin_attachment_id` int(11) unsigned DEFAULT NULL,
  `io` enum('I','O') DEFAULT 'I' COMMENT '类型 I input O output',
  PRIMARY KEY (`id`),
  KEY `fk_weixin_message_weixin_account1_idx` (`weixin_account_id`),
  KEY `fk_weixin_message_weixin1_idx` (`weixin_id`),
  KEY `status` (`status`,`dateline`),
  CONSTRAINT `fk_weixin_message_weixin_account1` FOREIGN KEY (`weixin_account_id`) REFERENCES `weixin_account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信消息';

/*Data for the table `weixin_message` */

/*Table structure for table `weixin_notification` */

DROP TABLE IF EXISTS `weixin_notification`;

CREATE TABLE `weixin_notification` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `to_weixin_id` int(11) unsigned NOT NULL,
  `from_weixin_id` int(11) unsigned NOT NULL,
  `message` varchar(1024) NOT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT '消息指向类型，如问答、站内通知、公告等',
  `read` enum('Y','N') DEFAULT 'N' COMMENT '是否已读',
  `dateline` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_notification_weixin1_idx` (`to_weixin_id`),
  KEY `fk_weixin_notification_weixin2_idx` (`from_weixin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信通知';

/*Data for the table `weixin_notification` */

/*Table structure for table `weixin_qrcode` */

DROP TABLE IF EXISTS `weixin_qrcode`;

CREATE TABLE `weixin_qrcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'scene_id 场景ID',
  `ticket` varchar(255) DEFAULT NULL COMMENT '票据',
  `path` varchar(255) DEFAULT NULL COMMENT '物理路径',
  `type` enum('S','U') DEFAULT 'U' COMMENT '类型 S系统 U用户 系统不可更改或删除 用户可更改',
  `description` varchar(255) DEFAULT NULL COMMENT '作用描述',
  `weixin_id` int(11) unsigned DEFAULT NULL,
  `scan_count` int(11) unsigned DEFAULT '0' COMMENT '扫描统计',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`(6))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信二维码';

/*Data for the table `weixin_qrcode` */

/*Table structure for table `weixin_waiting_input` */

DROP TABLE IF EXISTS `weixin_waiting_input`;

CREATE TABLE `weixin_waiting_input` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weixin_id` int(11) unsigned NOT NULL,
  `sign` varchar(45) NOT NULL COMMENT '标识，属于谁的等待　VoiceTopicData',
  `relation_id` int(11) unsigned DEFAULT NULL COMMENT '关联ID，与sign配置使用，确定关联对象',
  `callback` varchar(45) DEFAULT NULL COMMENT '回调函数',
  `dateline` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_weixin_waiting_input_weixin1_idx` (`weixin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='记录等待用户输入';

/*Data for the table `weixin_waiting_input` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
