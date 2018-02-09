<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 22/01/2018 10:25
 */

namespace wechat\classes\jobs;

use wechat\classes\model\Dc;
use wechat\classes\model\WxAccount;
use wulaphp\util\RedisClient;
use EasyWeChat\Factory;

class StatCronJob {
	protected static function menu() {
		//		$wx_ac   = new WxThAccount();
		//		$ac_info = $wx_ac->select('*')->where(['deleted' => 0])->limit(0, 1)->get();
		//		$options = ['app_id' => $ac_info ['wx_app_id'], 'secret' => $ac_info['wx_app_ecret'], 'token' => $ac_info['wx_token'], 'aes_key' => $ac_info['wx_en_key'], 'log' => ['level' => 'debug', 'file' => '/home/jk/wula/tmp/wx/tmp/wechat.log',],];
		//		$app     = new EasyWeChat\Foundation\Application($options);
		//		$menu    = $app->menu;
		//		$menus = $menu->all();
		//		print_r($menus);
		//		$menu->destroy();
		//		$menus = $menu->current();
		//		print_r($menus);
		//		$buttons = [["type" => "view", "name" => "QQ新闻", "url" => "https://news.qq.com/"], ["name" => "娱乐快讯", "sub_button" => [["type" => "view", "name" => "今日头条", "url" => "http://ent.qq.com/"], ["type" => "view", "name" => "视频", "url" => "https://v.qq.com/"], //					[
		//						"type" => "click",
		//						"name" => "赞一下我们",
		//						"key" => "V1001_GOOD"
		//					],
		//		],],];
		//		$menu->add($buttons);
	}

	public static function main() {
		$wx_ac         = new WxAccount();
		$size          = 10;
		$redis         = RedisClient::getRedis();
		$task_over_key = 'wx-ac-task-' . date('Y-m-d');
		$keep_time     = 3600 * 12;//12小时后过期,脚本暂定凌晨12点到1点间运行
		if (!$redis->exists($task_over_key)) {
			$redis->set($task_over_key, 0, $keep_time);
		}
		if ($redis->get($task_over_key) == 1) {
			return;
		}
		$page_key = 'wx-ac-page-' . date('Ymd');
		if (!$redis->exists($page_key)) {
			$redis->set($page_key, 0, $keep_time);
		}
		$redis->incr($page_key);
		$cur_page = $redis->get($page_key);
		$ac_info  = $wx_ac->select('*')->where(['deleted' => 0])->limit(($cur_page - 1) * $size, $size)->toArray();
		$bg_date  = date('Y-m-d', time() - 3600 * 24);
		foreach ($ac_info as $row) {
			if ($row['type'] == 0) {
				self::get_data_dev($row, $bg_date, $bg_date);
			}
			if ($row['type'] == 1) {
				self::get_data_auth($row, $bg_date, $bg_date);
			}
			sleep(1);
		}
		//最后一页数量不足,跑完后不需要再跑
		if (count($ac_info) < ($size - 1)) {
			$redis->set($task_over_key, 1, $keep_time);
		}
	}

	//dev
	protected static function get_data_dev(array $ac_info, $bg_date = '', $end_date = '') {

		$options = ['app_id' => $ac_info ['wx_app_id'], 'secret' => $ac_info['wx_app_ecret'], 'token' => $ac_info['wx_token'], 'aes_key' => $ac_info['wx_en_key'], 'log' => ['level' => 'debug', 'file' => '/home/jk/wula/tmp/wx/tmp/wechat.log',],];
		$app     = Factory::officialAccount($options);
		//userReadSummary 获取图文统计数据, 最大时间跨度：3;
		//userShareSummary 获取图文分享转发数据, 最大时间跨度：7;
		//userCumulate 获取累计用户数据, 最大时间跨度：7;
		//userSummary 获取用户增减数据, 最大时间跨度：7;
		if ($app) {
			self::_do_run($app, $bg_date, $end_date, $ac_info);
		}
	}

	//授权模式
	protected static function get_data_auth($ac_info, $bg_date = '', $end_date = '') {
		$config        = ['app_id' => 'wxcf10ab1fcbabe367', 'secret' => '6eac08eb3d0b2e8121504cb2581f0bcf', 'token' => 'jjdedfdfgr', 'aes_key' => 'fdghthhh457544frgeger25767fewfrftfsdswefref',];
		$open_platform = Factory::openPlatform($config);
		$app_id        = $ac_info ['wx_app_id'];
		$refresh_token = $ac_info ['wx_token'];
		$app           = $open_platform->officialAccount($app_id, $refresh_token);
		if ($app) {
			self::_do_run($app, $bg_date, $end_date, $ac_info);
		}
	}

	protected static function _do_run($app, $bg_date = '', $end_date = '', $ac_info) {
		$stat_list = [];
		//文章统计数据
		$rows = $app->data_cube->articleTotal($bg_date, $end_date);
		foreach ($rows['list'] as $val) {
			$k      = $val['ref_date'];
			$msg_id = $val['msgid'];
			$detail = $val['details'][0];
			if (isset($stat_list[ $k ]['target_user_total'])) {
				$stat_list[ $k ]['target_user_total'] += $detail['target_user'];
			} else {
				$stat_list[ $k ]['target_user_total'] = $detail['target_user'];
			}
			if (!isset($stat_list[ $k ]['article_detail'][ $msg_id ])) {
				$stat_list[ $k ]['article_detail'][ $msg_id ]['title'] = $val['title'];
				foreach ($detail as $i => $v) {
					$stat_list[ $k ]['article_detail'][ $msg_id ][ $i ] = $v;
					$tmp_zh_val                                         = self::usr_share($i);
					if (self::article_desc($i)) {
						$tmp_zh_val = self::article_desc($i);
					}
					if (self::usr_read($i)) {
						$tmp_zh_val = self::usr_read($i);
					}
					if ($tmp_zh_val) {
						$stat_list[ $k ]['article_detail'][ $msg_id ][ $i . '_zh' ] = $tmp_zh_val;
					}

				}
			}
		}
		//用户统计数据
		$rows = $app->data_cube->userSummary($bg_date, $end_date);
		foreach ($rows['list'] as $val) {
			$k = $val['ref_date'];
			if (!isset($stat_list[ $k ]['ref_date'])) {
				$stat_list[ $k ]['ref_date'] = $k;
			}
			//新增
			if (isset($stat_list[ $k ]['new_user_total'])) {
				$stat_list[ $k ]['new_user_total'] += $val['new_user'];
			} else {
				$stat_list[ $k ]['new_user_total'] = $val['new_user'];
			}
			//取关
			if (isset($stat_list[ $k ]['cancel_user_total'])) {
				$stat_list[ $k ]['cancel_user_total'] += $val['cancel_user'];
			} else {
				$stat_list[ $k ]['cancel_user_total'] = $val['cancel_user'];
			}
			//详情
			$ch = $val['user_source'];
			if (isset($stat_list[ $k ]['user_detail'][ $ch ])) {
				$stat_list[ $k ]['user_detail'][ $ch ]['cancel_user'] += $val['cancel_user'];
				$stat_list[ $k ]['user_detail'][ $ch ]['new_user']    += $val['new_user'];
			} else {
				$ch_zh                                                   = self::fans_source_to_zh($ch);
				$stat_list[ $k ]['user_detail'][ $ch ]['user_source_zh'] = $ch_zh;
				$stat_list[ $k ]['user_detail'][ $ch ]['user_source']    = $ch;
				$stat_list[ $k ]['user_detail'][ $ch ]['cancel_user']    = $val['cancel_user'];
				$stat_list[ $k ]['user_detail'][ $ch ]['new_user']       = $val['new_user'];
			}
		}
		//用户总和统计
		$rows = $app->data_cube->userCumulate($bg_date, $end_date);
		foreach ($rows['list'] as $val) {
			$k = $val['ref_date'];
			if (!isset($stat_list[ $k ]['cumulate_user'])) {
				$stat_list[ $k ]['cumulate_user'] = $val['cumulate_user'];
			}
		}
		echo "\n\n\n";
		echo json_encode($stat_list);
		echo "\n\n\n";
		$log            = [];
		$log['wx_aid']  = $ac_info['id'];
		$log['wx_name'] = $ac_info['wx_name'];
		$log['type']    = $ac_info['wx_type'];
		$log['remark']  = date('Y-m-d H:i:s') . 'opt';
		$dc             = new Dc();
		foreach ($stat_list as $d => $val) {
			$log['cont']     = json_encode($val);
			$log['sta_date'] = strtotime($d);
			$dc->create($log);
		}
	}

	//粉丝来源转成对应汉字
	protected static function fans_source_to_zh($ch = 0) {
		$ch          = (int)$ch;
		$source_list = ['0' => '其他合计', '1' => '公众号搜索', '17' => '名片分享', '30' => '扫描二维码', '43' => '图文页右上角菜单', '51' => '支付后关注（在支付完成页)', '57' => '图文页内公众号名称', '75' => '公众号文章广告', '78' => '朋友圈广告'];
		$ch_zh       = $source_list['0'];
		if (isset($source_list[ $ch ])) {
			$ch_zh = $source_list[ $ch ];
		}

		return $ch_zh;
	}

	//文章统计汇总官方数据
	protected static function article_desc($type = '') {
		$index = trim($type);
		$list  = ['stat_date' => '统计的日期', 'int_page_read_user' => '图文页的阅读人数', 'int_page_read_count' => '图文页的阅读次数', 'ori_page_read_user' => '原文页的阅读人数，无原文页时此处数据为0', 'ori_page_read_count' => '原文页的阅读次数', 'share_scene' => '分享的场景 1代表好友转发 2代表朋友圈 3代表腾讯微博 255代表其他', 'share_user' => '分享的人数', 'share_count' => '分享的次数', 'add_to_fav_user' => '收藏的人数', 'add_to_fav_count' => '收藏的次数', 'target_user' => '送达人数',];
		$ret   = '';
		if (isset($list[ $index ])) {
			$ret = $list[ $index ];
		}

		return $ret;
	}

	//阅读次数
	protected static function usr_read($type = 'int_page_from_other_read_user') {
		$index     = trim(str_replace('_', '', $type));
		$read_list = ['intpagefromsessionreaduser' => '公众号会话阅读人数', 'intpagefromsessionreadcount' => '公众号会话阅读次数', 'intpagefromhistmsgreaduser' => '历史消息页阅读人数', 'intpagefromhistmsgreadcount' => '历史消息页阅读次数', 'intpagefromfeedreaduser' => '朋友圈阅读人数', 'intpagefromfeedreadcount' => '朋友圈阅读次数', 'intpagefromfriendsreaduser' => '好友转发阅读人数', 'intpagefromfriendsreadcount' => '好友转发阅读次数', 'intpagefromotherreaduser' => '其他场景阅读人数', 'intpagefromotherreadcount' => ' 其他场景阅读次数',];
		$ret       = '';
		if (isset($read_list[ $index ])) {
			$ret = $read_list[ $index ];
		}

		return $ret;
	}

	//转发人数
	protected static function usr_share($type = 'feed_share_from_session_user') {
		$index     = trim(str_replace('_', '', $type));
		$read_list = ['feedsharefromsessionuser' => '公众号会话转发朋友圈人数', 'feedsharefromsessioncnt' => '公众号会话转发朋友圈次数', 'feedsharefromfeeduser' => '朋友圈转发朋友圈人数', 'feedsharefromfeedcnt' => '朋友圈转发朋友圈次数', 'feedsharefromotheruser' => '其他场景转发朋友圈人数', 'feedsharefromothercnt' => '其他场景转发朋友圈次数',];
		$ret       = '';
		if (isset($read_list[ $index ])) {
			$ret = $read_list[ $index ];
		}

		return $ret;
	}
}
