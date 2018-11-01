<?php
/**
 * @desc  .
 * @author: FLY
 * @Date  : 08/01/2018 18:37
 */

namespace wechat\controllers;

use EasyWeChat\Factory;
use EasyWeChat\OpenPlatform\Server\Guard;
use wechat\model\WxGrant;
use weixin\classes\form\WxAccountForm;
use weixin\classes\WxAccount;
use weixin\classes\WxCache;
use wulaphp\app\App;
use wulaphp\mvc\controller\Controller;

class ServiceController extends Controller {
    /**@var \EasyWeChat\OpenPlatform\Application */
    private $open_plat_form;
    private $baseURL;
    private $cache;

    public function beforeRun($action, $refMethod) {
        parent::beforeRun($action, $refMethod);
        $config        = App::acfg('openPlatform@weixin');
        $this->baseURL = $config['url'];
        unset($config['url']);
        $config               = array_merge($config, [
            'response_type' => 'array',
            'log'           => [
                'level' => 'error',
                'file'  => LOGS_PATH . 'wechat.opl.log',
            ],
            'http'          => [
                'max_retries' => 2,
                'retry_delay' => 1000,
                'timeout'     => 15
            ]
        ]);
        $this->open_plat_form = Factory::openPlatform($config);
        try {
            $this->cache = new WxCache();
            $this->open_plat_form->rebind('cache', $this->cache);
        } catch (\Exception $e) {
            // 使用默认缓存
        }
    }

    /**
     * 公众号或小程序消息和事件推送
     *
     * @param string $appid 公众号或小程序
     * @param string $opt   目前只有callback
     *
     * @return string
     * @throws
     */
    public function index($appid, $opt = '') {
        if ($opt == 'callback') {
            $appid           = trim($appid);
            $openPlatform    = $this->open_plat_form;
            $officialAccount = $openPlatform->officialAccount($appid);
            if ($this->cache) {
                $officialAccount->rebind('cache', $this->cache);
            }
            $server = $officialAccount->server;
            WxAccount::onEvent($server, $appid);
            @ob_start();
            $server->serve()->send();

            return ob_get_clean();
        }

        return null;
    }

    /**
     * 用于接收取消授权通知、授权成功通知、授权更新通知，也用于接收ticket，ticket是验证平台方的重要凭据。
     *
     * @see callback
     * @return string
     * @throws
     */
    public function msg() {
        return $this->callback();
    }

    /**
     * 用于接收取消授权通知、授权成功通知、授权更新通知，也用于接收ticket，ticket是验证平台方的重要凭据。
     *
     * @return string
     * @throws
     */
    public function callback() {
        $openPlatform = $this->open_plat_form;
        @ob_start();
        $server = $openPlatform->server;
        // 处理授权成功事件
        $server->push(function ($message) {
            $this->authorized($message['AuthorizationCode'], false);

            return 'success';
        }, Guard::EVENT_AUTHORIZED);

        // 处理授权更新事件
        $server->push(function ($message) {
            $this->authorized($message['AuthorizationCode']);

            return 'success';
        }, Guard::EVENT_UPDATE_AUTHORIZED);

        // 处理授权取消事件
        $server->push(function ($message) {
            $this->unauthorized($message['AuthorizerAppid']);

            return 'success';
        }, Guard::EVENT_UNAUTHORIZED);

        $server->serve()->send();

        return ob_get_clean();
    }

    /**
     * 扫码授权回调
     *
     * @param string $auth_code
     *
     * @return \wulaphp\mvc\view\View
     */
    public function authorize($auth_code) {
        $info = $this->authorized($auth_code);
        if ($info && $info['nick_name']) {
            $msg = $info['nick_name'];
        } else {
            $msg = false;
        }

        return view(['msg' => $msg]);
    }

    /**
     * 授权链接
     *
     * @param string $uid
     *
     * @return \wulaphp\mvc\view\View
     */
    public function grant($uid = '') {
        $openPlatform = $this->open_plat_form;
        // 预授权URL
        if ($uid) {
            $url = $this->baseURL . App::url('wechat/service/authorize/' . intval($uid));
        } else {
            $url = $this->baseURL . App::url('wechat/service/authorize');
        }
        $link = $openPlatform->getPreAuthorizationUrl($url);

        return view(['link' => $link]);
    }

    /**
     * 用户授权处理.
     *
     * @param string $auth_code 授权码
     * @param bool   $update    是否是更新
     *
     * @return array
     */
    private function authorized($auth_code, $update = true) {
        $openPlatform = $this->open_plat_form;
        $info         = $openPlatform->handleAuthorize($auth_code);
        if ($info) {
            if (isset($info['authorization_info']) && $info['authorization_info']) {
                $ac_info       = $info['authorization_info'];
                $app_id        = $ac_info['authorizer_appid'];
                $refresh_token = $ac_info['authorizer_refresh_token'];
                $access_token  = $ac_info['authorizer_access_token'];
                $all_ac_info   = $openPlatform->getAuthorizer($app_id);
                if ($all_ac_info && isset($all_ac_info['authorizer_info'])) {
                    $wx_ac_info = $all_ac_info['authorizer_info'];
                    if ($update) {
                        $acc['name']      = $wx_ac_info['nick_name'];
                        $acc['wxid']      = $wx_ac_info['alias'] ?? (empty($wx_ac_info['alias']) ? $app_id : $wx_ac_info['alias']);
                        $acc['origin_id'] = $wx_ac_info['user_name'];
                        $acc['app_id']    = $app_id;
                        $acc['platform']  = 'PL';
                        if ($wx_ac_info['service_type_info']['id'] <= 1) {
                            $acc['type'] = 'DY';
                        } else {
                            $acc['type'] = 'FW';
                        }
                        $acc['signature']      = $wx_ac_info['signature'] ?? '';
                        $acc['principal_name'] = $wx_ac_info['principal_name'] ?? '个人';
                        $acc['avatar']         = $wx_ac_info['head_img'] ?? '';
                        $acc['qrcode']         = $wx_ac_info['qrcode_url'] ?? '';
                        $acc['token']          = rand_str(24);

                        if ($wx_ac_info['verify_type_info']['id'] >= 0) {
                            $acc['authed'] = 1;
                        } else {
                            $acc['authed'] = 0;
                        }

                        $acc['update_time'] = time();
                        $acc['update_uid']  = 0;
                        $wxAccount          = new WxAccountForm();
                        $db                 = $wxAccount->db();
                        $db->start();
                        if ($wxAccount->exist(['app_id' => $app_id], 'id')) {
                            $rst = $wxAccount->updateAccount($acc, ['app_id' => $app_id]);
                        } else {
                            $acc['create_time'] = $acc['update_time'];
                            $acc['create_uid']  = $acc['update_uid'];
                            $rst                = $wxAccount->newAccount($acc);
                        }
                        if ($rst) {
                            //更新本次授权数据
                            $grant['appid']         = $app_id;
                            $grant['access_token']  = $access_token;
                            $grant['refresh_token'] = $refresh_token;
                            $grant['expire']        = time() + (int)($wx_ac_info['expires_in'] ?? 0);
                            $grant['func_info']     = json_encode($all_ac_info['authorization_info']['func_info']);

                            $wxGrant = new WxGrant();
                            $rst     = $wxGrant->grant($grant);
                        } else {
                            log_error($wxAccount->lastError(), 'authorized');
                        }

                        if ($rst) {
                            $db->commit();
                        } else {
                            $db->rollback();

                            return [];
                        }
                    }

                    return $wx_ac_info;
                } else {
                    log_error("获取公众号详细信息失败:\n" . var_export($info, true), 'authorized');

                    return [];
                }
            } else {
                log_error("获取公众号信息失败:\n" . var_export($info, true), 'authorized');

                return [];
            }
        } else {
            log_error('获取公众号信息失败:' . $auth_code, 'authorized');
        }

        return $info;
    }

    /**
     * 取消授权处理.
     *
     * @param string $appid
     */
    private function unauthorized($appid) {
        $wxGrant = new WxGrant();
        $wxGrant->revoke($appid);
    }
}