<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use backend\classes\IFramePageController;

class DcController extends IFramePageController {
	public function index() {
		$data = [];

		return $this->render($data);
	}

	public function data($page = 1, $page_size = 20, $sort_name = 'id', $sort_type = 'd') {
		$data = [];

		return view($data);
	}

}