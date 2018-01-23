<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use EasyWeChat\Foundation\Application;
use wechat\classes\model\WxAccount;
use wulaphp\mvc\controller\Controller;

class ServiceController extends Controller {
	public function index($ac=''){
		$ac= trim($ac);
		$wx_ac  = new WxAccount();
		$ac_info=$wx_ac->select('*')->where(['wx_nick'=>$ac])->limit(0,1)->get();
		$config = [
			'app_id' =>$ac_info ['wx_app_id'],
			'secret' => $ac_info['wx_app_ecret'],
			'token'  => $ac_info['wx_token'],
			'aes_key'=>$ac_info['wx_en_key'],
			'log' => [
				'level' => 'debug',
				'file' => '/home/jk/wula/tmp/wx/tmp/wechat.log',
			],
		];

		$app =new Application($config);
		$app->server->setMessageHandler(function ($msg){
			if($msg){
				return 'fk,关注我,点我!';
			}else{
				return '谢谢关注!';
			}
		});
		$res = $app->server->serve();
		return $res->send();
	}

}