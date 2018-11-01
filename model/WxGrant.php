<?php
/*
 * This file is part of wulacms.
 *
 * (c) Leo Ning <windywany@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace wechat\model;

use wulaphp\db\Table;

class WxGrant extends Table {
    protected $autoIncrement = false;
    protected $primaryKeys   = ['appid'];

    /**
     * 授权数据
     *
     * @param array $data
     *
     * @return bool
     */
    public function grant(array $data) {
        $appid = $data['appid'] ?? '';
        if (!$appid) {
            return false;
        }
        $data['update_time'] = time();
        if ($this->exist(['appid' => $appid], 'appid')) {
            return $this->update($data);
        } else {
            $data['create_time'] = $data['update_time'];

            return $this->insert($data);
        }
    }

    /**
     * 删除授权.
     *
     * @param string $appid
     *
     * @return bool
     */
    public function revoke($appid) {
        return $this->delete(['appid' => $appid]);
    }
}