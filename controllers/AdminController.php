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

/**
 * Class AdminController
 * @package wechat\controllers
 * @acl     m:wx
 */
class AdminController extends IFramePageController {

    public function index($id) {
        $data['id'] = $id;

        return $this->render($data);
    }
}