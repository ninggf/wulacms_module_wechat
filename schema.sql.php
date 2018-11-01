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

$tables['1.0.0'][] = "CREATE TABLE `{prefix}wx_grant` (
  `appid` varchar(64) NOT NULL COMMENT 'APPID',
  `access_token` TEXT NOT NULL COMMENT 'access token',
  `refresh_token` TEXT NOT NULL COMMENT 'refresh token',
  `expire`      INT UNSIGNED NOT NULL COMMENT 'access token过期时间',
  `func_info` TEXT DEFAULT NULL COMMENT '授权给开发者的权限集列表',
  `create_time` int(10) DEFAULT '0',
  `update_time` int(10) DEFAULT '0',
  PRIMARY KEY (`appid`),
  INDEX `IDX_EXPIRE` (`expire`)
) ENGINE=InnoDB DEFAULT CHARACTER SET={encoding} COMMENT '第三方授权公众号原始数据'";
