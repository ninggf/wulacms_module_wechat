<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 14:08
 */

namespace wechat\controllers;

use backend\classes\IFramePageController;
use wechat\classes\model\Fans;
use wechat\classes\model\WxAccount;
use wulaphp\io\Ajax;

class FansController extends IFramePageController {
	public function index() {
		$data = [];

		return $this->render($data);
	}

	public function data() {
		$page_size       = rqst('pager[size]', 20);
		$page            = rqst('pager[page]', 1);
		$q               = rqst('q', '');
		$ret             = [];
		$wx_ac           = new Fans();
		$cond            = [];
		$cond['deleted'] = 0;
		if ($q) {
			$where1['wx_name LIKE']   = '%' . $q . '%';
			$where1['||wx_nick LIKE'] = '%' . $q . '%';
			$cond[]                   = $where1;
		}
		$query = $wx_ac->select('*')->where($cond)->limit(($page - 1) * $page_size, $page_size)->desc('id');
		$count = $query->count();
		$rows  = $query->toArray();

		$ret['count'] = $count;
		$ret['rows']  = $rows;

		return view($ret);
	}

	//编辑或新增
	public function edit($id = 0) {
		$data = [];
		if ($id) {
			$wx_ac       = new WxAccount();
			$data['row'] = $wx_ac->get($id);
		}

		return view($data);
	}

	//保存
	public function save() {
		$data                = rqsts(['wx_name', 'id', 'wx_nick', 'wx_app_id', 'wx_token', 'wx_app_ecret', 'wx_en_key', 'remark', 'status', 'wx_type']);
		$id                  = (int)$data['id'];
		$wx_ac               = new WxAccount();
		$ret                 = true;
		$data['update_time'] = time();
		if ($id) {
			$et = $wx_ac->up_acc($data);
		} else {
			$data['create_time'] = time();
			unset($data['id']);
			$ret = $wx_ac->create($data);
		}

		if ($ret) {
			return Ajax::success();
			//Ajax::reload('#core-admin-table','操作成功!');
		} else {
			return Ajax::error('操作失败!');
		}
	}

	//删除
	public function del($id = 0) {
		$id  = (int)$id;
		$ret = true;
		if ($id) {
			$wx_ac = new WxAccount();
			$ret   = $wx_ac->del($id);
		}
		if ($ret) {
			return Ajax::reload('#core-admin-table', 'ok');
		} else {

			return Ajax::error('操作失败!');
		}
	}
}