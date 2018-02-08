<?php
/*
 * This file is part of wulacms.
 *
 * (c) Leo Ning <windywany@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

defined('APPROOT') or header('Page Not Found', true, 404) || die();

$tables ['1.0.0'] [] = "CREATE TABLE `{prefix}wx_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wx_name` varchar(255) DEFAULT '' COMMENT '公众号名称',
  `wx_nick` varchar(255) DEFAULT '' COMMENT '非汉字名称',
  `wx_type` tinyint(4) DEFAULT '0' COMMENT '0订阅号1服务号3私人',
  `wx_app_id` varchar(50) DEFAULT '' COMMENT '微信appID',
  `wx_app_ecret` varchar(255) DEFAULT '' COMMENT '密钥',
  `wx_token` varchar(100) DEFAULT '' COMMENT '令牌',
  `wx_en_key` varchar(255) DEFAULT '' COMMENT '消息加解密密钥',
  `type` tinyint(2) DEFAULT '0' COMMENT '0dev模式1第三方授权模式',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `status` tinyint(4) DEFAULT '0' COMMENT '0正常1禁用',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '0正常1删除',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公众号列表'";
$tables ['1.0.0'] [] = "CREATE TABLE `{prefix}wx_stat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wx_aid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `wx_name` varchar(255) DEFAULT '' COMMENT '公众号名称',
  `type` tinyint(4) DEFAULT '0' COMMENT '公众号类型0订阅号1服务号3私人',
  `cont` text COMMENT '统计内容,json',
  `sta_date` int(11) DEFAULT '0' COMMENT '归档数据日期',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '0正常1删除',
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='公众号统计分析';";
$tables['1.0.0'][]="CREATE TABLE `wx_th_meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wx_id` varchar(255) DEFAULT '0',
  `origin_data` text,
  `remark` varchar(255) DEFAULT '',
  `deleted` tinyint(2) DEFAULT '0',
  `create_time` int(10) DEFAULT '0',
  `update_time` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方授权公众号原始数据';";
