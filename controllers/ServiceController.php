<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use EasyWeChat\Factory;
use wechat\classes\model\WxAccount;
use wechat\classes\model\WxThAccount;
use wulaphp\app\Module;
use wulaphp\mvc\controller\Controller;

class ServiceController extends Controller {
	protected $open_plat_form;

	function __construct(\wulaphp\app\Module $module) {
		parent::__construct($module);
		$config               = ['app_id' => 'wxcf10ab1fcbabe367', 'secret' => '6eac08eb3d0b2e8121504cb2581f0bcf', 'token' => 'jjdedfdfgr', 'aes_key' => 'fdghthhh457544frgeger25767fewfrftfsdswefref',];
		$open_platform        = Factory::openPlatform($config);
		$this->open_plat_form = $open_platform;
	}

	public function index($ac = '', $opt = 'callback') {
		$ac = trim($ac);
		//		$wx_ac  = new WxAccount();
		//		$ac_info=$wx_ac->select('*')->where(['wx_nick'=>$ac])->limit(0,1)->get();
		//		$app->server->setMessageHandler(function ($msg){
		//			if($msg){
		//				return '谢谢关注!';
		//			}else{
		//				return '谢谢关注!';
		//			}
		//		});
		//		$openPlatform->server->setMessageHandler(function($event) {
		//			switch ($event->InfoType) {
		//				case 'authorized':
		//					break;
		//				case 'unauthorized':
		//					break;
		//				case 'updateauthorized':
		//					break;
		//				case 'component_verify_ticket':
		//					break;
		//			}
		//		});
		//		$openPlatform = $this->open_plat_form;
		//		return $openPlatform->server->serve()->send();

		$openPlatform    = $this->open_plat_form;
		$officialAccount = $openPlatform->officialAccount($ac);
		$server          = $officialAccount->server;

		$server->push(function () {
			return '谢谢关注!';
		});

		return $server->serve()->send();
	}

	//授权事件接收URL
	public function msg() {
		$openPlatform = $this->open_plat_form;

		return $openPlatform->server->serve()->send();
	}

	//授权码
	public function auth_code() {
		$openPlatform = $this->open_plat_form;
		$info         = $openPlatform->handleAuthorize($authorizationCode = null);
		if ($info['authorization_info']) {
			$ac_info       = $info['authorization_info'];
			$app_id        = $ac_info['authorizer_appid'];
			$refresh_token = $ac_info['authorizer_refresh_token'];
			$all_ac_info   = $openPlatform->getAuthorizer($app_id);
			$wx_ac_info    = $all_ac_info['authorizer_info'];
			//授权方公众号类型，0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号
			$log              = [];
			$log['wx_app_id'] = $app_id;
			$log['wx_token']  = $refresh_token;
			$log['wx_name']   = $wx_ac_info['nick_name'];
			$log['wx_nick']   = $wx_ac_info['alias'];
			$log['wx_type']   = 0;//默认是订阅号
			if ($wx_ac_info['service_type_info']['id'] == 2) {
				$log['wx_type'] = 1;//服务号
			}
			$log['remark']      = '其他的信息json格式存入扩展表';
			$log['type']        = 1;
			$log['update_time'] = time();
			$ac_model           = new WxAccount();
			$check_id           = $ac_model->select('id')->where(['wx_app_id' => $app_id])->get('id');
			if ($check_id) {
				$log['id'] = $check_id;
				$ac_model->up_acc($log);
			} else {
				$log['create_time'] = time();
				$ac_model->create($log);
			}

			$th_model           = new WxThAccount();
			$log                = [];
			$log['update_time'] = time();
			$log['wx_id']       = $app_id;
			$log['origin_data'] = json_encode($all_ac_info);
			$log['remark']      = 'json格式存入';
			$check_id           = $th_model->select('id')->where(['wx_id' => $app_id])->get('id');
			if ($check_id) {
				$log['id'] = $check_id;
				$th_model->up_acc($log);
			} else {
				$log['create_time'] = time();
				$th_model->create($log);
			}
			$msg = 'ok';
		} else {
			$msg = 'try it again!';
		}

		return view(['msg' => $msg]);
	}

	//链接
	public function auth_link() {
		$openPlatform = $this->open_plat_form;
		$url          = 'http://wechat.juwang.com/wechat/service/auth_code';
		$link         = $openPlatform->getPreAuthorizationUrl($url);

		return view(['link' => $link]);
	}

	public function info() {
		$openPlatform = $this->open_plat_form;
//		$appId        = 'wx3ffe452161144cb0';
//		$refreshToken = 'refreshtoken@@@B8iJ4nYaXywZKPUDNIY83rKImLU2jjmhGuBJ0qQJHEc';
//		$wx_ac_info   = $openPlatform->getAuthorizer($appId);
//		echo json_encode($wx_ac_info);
		//		$officialAccount = $openPlatform->officialAccount($appId, $refreshToken);
		//		$bg_date         = '2016-08-05';
		//		$end_date        = '2016-08-05';
		//		$ret             = $officialAccount->data_cube->articleTotal($bg_date, $end_date);
		//		$rows            = $ret;
		//		print_r($rows);

		return $openPlatform->server->serve()->send();
	}

}