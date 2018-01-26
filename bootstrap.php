<?php

namespace wechat;

use backend\classes\DashboardUI;
use wula\cms\CmfModule;
use wulaphp\app\App;

/**
 * Class WechatModule
 * @package wechat
 * @group   cms
 */
class WechatModule extends CmfModule {
	public function getName() {
		return '微信';
	}

	public function getDescription() {
		return '基于easywechat4.0';
	}

	public function getHomePageURL() {
		return '模块的URL';
	}

	/**
	 * @param \backend\classes\DashboardUI $ui
	 *
	 * @bind dashboard\initUI
	 */
	public static function initUI(DashboardUI $ui) {
		$passport = whoami('admin');
		if ($passport->cando('m:wechat')) {
			$menu       = $ui->getMenu('wechat', '微信');
			$menu->icon = '&#xe63e;';
			if ($passport->cando('acc:wechat')) {
				$list              = $menu->getMenu('account', '公众号管理');
				$list->icon        = '&#xe63e;';
				$list->data['url'] = App::url('wechat/account');
			}
//			if ($passport->cando('fan:wechat')) {
//				$list              = $menu->getMenu('fans', '公众号粉丝');
//				$list->icon        = '&#xe630;';
//				$list->data['url'] = App::url('wechat/fans');
//			}
			if ($passport->cando('dc:wechat')) {
				$list              = $menu->getMenu('dc', '数据统计');
				$list->icon        = '&#xe74d;';
				$list->data['url'] = App::url('wechat/dc');
			}
		}
	}

	/**
	 * @param \wulaphp\auth\AclResourceManager $manager
	 *
	 * @bind rbac\initAdminManager
	 */
	public static function initAcl($manager) {
		$acl = $manager->getResource('wechat', '微信', 'm');
		$acl->addOperate('acc', '公众号管理');
//		$acl->addOperate('fan', '公众号粉丝');
		$acl->addOperate('dc', '数据统计与分析');
	}
}

App::register(new WechatModule());