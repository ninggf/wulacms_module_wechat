<?php
/*
 * This file is part of wulacms.
 *
 * (c) Leo Ning <windywany@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace wechat\controllers;

use backend\classes\IFramePageController;
use weixin\classes\WxAccount;
use wulaphp\app\App;

/**
 * Class AccountController
 * @package wechat\controllers
 * @acl     m:wx
 */
class AccountController extends IFramePageController {
    public function index() {
        $types             = WxAccount::TYPES;
        $data['types']     = $types;
        $data['plateform'] = 'PL';
        $data['grantURL']  = App::cfg('openPlatform.url@weixin') . App::url('wechat/service/grant');

        return $this->render($data);
    }

    public function data($type = '', $wxid = '', $authed = '', $count = '') {
        $where             = [];
        $query             = App::db()->select('*')->from('{wx_account}')->page()->sort();
        $where['platform'] = 'PL';

        if ($type) {
            $where['type'] = $type;
        }

        if ($wxid) {
            $where['wxid'] = $wxid;
        }

        if (is_numeric($authed)) {
            $where['authed'] = intval($authed);
        }

        $query->where($where);
        $rows  = $query->toArray();
        $total = '';
        if ($count) {
            $total = $query->total('id');
        }

        $data['total']    = $total;
        $data['rows']     = $rows;
        $data['types']    = WxAccount::TYPES;
        $data['base_url'] = App::cfg('base_url@wx');
        $data['modes']    = ['T' => '明文模式', 'C' => '兼容模式', 'S' => '安全模式'];
        $data['canEdit']  = $this->passport->cando('e:wx/account');

        return view($data);
    }
}