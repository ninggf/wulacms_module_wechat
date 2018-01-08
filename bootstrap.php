<?php
namespace wechat;

use backend\classes\DashboardUI;
use wula\cms\CmfModule;
use wulaphp\app\App;

class WechatModule extends CmfModule {
    public function getName() {
        return '微信';
    }

    public function getDescription() {
        return '模块的描述';
    }

    public function getHomePageURL() {
        return '模块的URL';
    }

	/**
	 * @param \backend\classes\DashboardUI $ui
	 * @bind dashboard\initUI
	 */
    public static function initUI(DashboardUI $ui){
	    $passport = whoami('admin');
	    if($passport->cando('m:wechat')) {
		    $menu              = $ui->getMenu('wechat', '微信');
		    $menu->icon        = '&#xe63e;';
		    if($passport->cando('acc:wechat')) {
			    $list              = $menu->getMenu('account', '公众号管理');
			    $list->icon        = '&#xe63e;';
			    $list->data['url'] = App::url('wechat/account');
		    }
	    }
    }
	/**
	 * @param \wulaphp\auth\AclResourceManager $manager
	 *
	 * @bind rbac\initAdminManager
	 */
    public static function initAcl($manager){
    	$acl = $manager->getResource('wechat','微信','m');
    	$acl->addOperate('acc','公众号管理');
    }
}

App::register(new WechatModule());