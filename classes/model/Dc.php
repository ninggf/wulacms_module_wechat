<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 11/01/2018 10:45
 */

namespace wechat\classes\model;

use wulaphp\db\Table;

class Dc extends Table {
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
	 * @param  array $data
	 *
	 * @return  bool $ret
	 */
	public function del($data) {
		$ret = false;
		if (isset($data['id'])) {
			$ret = $this->up($data);
		}

		return $ret;
	}
}