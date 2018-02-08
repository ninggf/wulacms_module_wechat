<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 09/01/2018 14:59
 */

namespace wechat\classes\model;

use wulaphp\db\Table;
use wulaphp\db\DatabaseConnection;

class WxThAccount extends Table {
	public $table = 'wx_th_meta';
	/**
	 * 更新微信公众号信息
	 *
	 * @param null $data
	 *
	 * @return bool|\wulaphp\db\sql\UpdateSQL
	 */
	public function up_acc($data = null) {
		try {
			$id = (int)$data['id'];
			if ($id) {
				return $this->update($data, ['id' => $id]);
			} else {
				return false;
			}

		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * 创建新的公众号
	 *
	 * @param   array $data
	 *
	 * @return bool|int
	 */
	public function create($data) {
		$id = $this->trans(function (DatabaseConnection $db) use ($data) {
			$id = $this->insert($data);
			if (!$id) {
				return false;
			}

			return $id;
		});

		return $id;
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