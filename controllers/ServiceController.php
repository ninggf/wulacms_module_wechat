<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use EasyWeChat\Factory;
use wulaphp\mvc\controller\Controller;

class ServiceController extends Controller {
	public function index(){
		$config = [
			'app_id' => 'wx3cf0f39249eb0xxx',
			'secret' => 'f1c242f4f28f735d4687abb469072xxx',
			'response_type' => 'array',
			'log' => [
				'level' => 'debug',
				'file' => __DIR__.'/wechat.log',
			],
		];

		$app = Factory::officialAccount($config);
		return $app->server->serve();
	}

}