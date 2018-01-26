<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 11/01/2018 10:45
 */

namespace wechat\classes\model;

use wulaphp\db\Table;

class Dc extends Table {
	public  $table = 'wx_stat_log';

	/**
	 * @param  array $data
	 *
	 * @return  bool $ret
	 */
	public function create($data) {
		$ret = false;
		if (empty($data)) {
			return $ret;
		}
		$data['create_time'] = time();
		$data['update_time'] = $data['create_time'];
		$ret                 = $this->insert($data);

		return $ret;
	}

	/**
	 * @param array $data
	 *
	 * @return  bool $ret
	 */
	public function up($data = []) {
		$ret = false;
		if (isset($data['id'])) {
			$data['update_time'] = time();
			$id                  = (int)$data['id'];
			unset($data['id']);
			$ret = $this->update($data, ['id' => $id]);
		}

		return $ret;
	}

	/**
	 * 删除
	 *
	 * @param int $id
	 *
	 * @return  bool
	 */
	public function del($id = 0) {
		$id = (int)$id;
		if ($id) {
			$data                = [];
			$data['update_time'] = time();
			$data['deleted']     = 1;
			$data['remark']      = date('Y-m-d H:i:s') . ' adm opt';

			return $this->update($data, ['id' => $id]);
		}

		return false;

	}
}