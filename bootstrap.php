<?php

namespace wechat;

use wula\cms\CmfModule;
use wulaphp\app\App;

/**
 * Class WechatModule
 * @package wechat
 * @group   cms
 */
class WechatModule extends CmfModule {
    public function getName() {
        return '微信三方';
    }

    public function getDescription() {
        return '基于easywechat4.0';
    }

    public function getHomePageURL() {
        return '';
    }

    public function getVersionList() {
        $v['1.0.0'] = '初始化';

        return $v;
    }

    /**
     * @param \backend\classes\DashboardUI $ui
     *
     * @bind dashboard\initUI
     */
    public static function initMenu($ui) {
        $passport = whoami('admin');
        if ($passport->cando('m:wx')) {
            $wx = $ui->getMenu('apps/wx', 'Wechat', 1);
            if ($passport->cando('m:wx/account')) {
                $acc              = $wx->getMenu('pl', '三方平台', 3);
                $acc->icon        = '&#xe626;';
                $acc->iconCls     = 'alicon';
                $acc->iconStyle   = 'color:orange';
                $acc->data['url'] = App::url('wechat/account');
            }
        }
    }
}

App::register(new WechatModule());