<?php
/**
 * @desc  .微信推送统计控制器
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use backend\classes\IFramePageController;
use wechat\classes\model\Dc;
use wulaphp\io\Ajax;
use wulaphp\io\Response;

class DcController extends IFramePageController {
	function __construct(\wulaphp\app\Module $module) {
		parent::__construct($module);
		if(!$this->passport->cando('acc:wechat')){
			Response::respond(404);
		}
	}
	public function index() {
		$data = [];

		return $this->render($data);
	}

	public function data($page = 1, $page_size = 20, $sort_name = 'id', $sort_type = 'd') {
		$data            = [];
		$dc_log          = new Dc();
		$q               = rqst('q', '');
		$date            = rqst('date', '');
		$cond['deleted'] = 0;
		if ($date) {
			$cond['sta_date'] = strtotime($date);
		}
		if ($q) {
			$where1['wx_name LIKE'] = '%' . $q . '%';
			$where1['||cont LIKE']  = '%' . $q . '%';
			$cond[]                 = $where1;
		}
		$query = $dc_log->select('*')->where($cond)->limit(($page - 1) * $page_size, $page_size)->sort($sort_name, $sort_type);
		$count = $query->count();
		$rows  = $query->toArray();
		foreach ($rows as &$row) {
			$tmp_cont                 = json_decode($row['cont'], true);
			$row['target_user_total'] = $tmp_cont['target_user_total'];
			$row['new_user_total']    = $tmp_cont['new_user_total'];
			$row['cancel_user_total'] = $tmp_cont['cancel_user_total'];
			$row['cumulate_user']     = $tmp_cont['cumulate_user'];
			$row['article_detail']    = $tmp_cont['article_detail'];
			$row['user_detail']       = $tmp_cont['user_detail'];
		}

		$data['count'] = $count;
		$data['rows']  = $rows;

		return view($data);
	}
	//删除
	public function del($id = 0) {
		$id  = (int)$id;
		$ret = true;
		if ($id) {
			$wx_ac = new Dc();
			$ret   = $wx_ac->del($id);
		}
		if ($ret) {
			return Ajax::reload('#core-admin-table', 'ok');
		} else {

			return Ajax::error('操作失败!');
		}
	}

}