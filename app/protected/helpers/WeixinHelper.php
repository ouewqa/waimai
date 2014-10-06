<?php


class WeixinHelper
{

    public $startTime, $execute_time; #记录程序执行时间


    public $signature;
    public $timestamp;
    public $nonce;
    public $echostr;
    public $token;
    public $scene_id = false; #二维码邀请时带回来的场景值


    //
    public $access_token; #有效期7200秒，需要定时更新
    public $access_token_expire_time; #有效期7200秒，需要定时更新

    public $debug = false; #
    public $msg = null; #获取到的信息
    public $flag = false; #星标，如果用户请求未响应可设置为真，方便公众后台查阅

    public $appid;
    public $appsecret;

    public $account; #微信自建平台账号信息
    public $user; #当前联结到微信平台的账号

    public $message = null;

    public $new_subscribe = true;

    public $callback, $callbackParams; #回调方法用

    /**
     * __construct
     *
     * @param WeixinAccount $account 微信公众账户
     * @throws CHttpException
     * @access public
     * @return \WeixinHelper
     */
    public function __construct(WeixinAccount $account)
    {
        $this->account = $account;
        $this->startTime = microtime(true);

        #微信参数
        $this->debug = $this->account->debug == 'Y';
        $this->token = $this->account->token;
        $this->appid = $this->account->appid;
        $this->appsecret = $this->account->appsecret;

        #获取AccessToken数据
        $this->access_token = $this->account->access_token;
        $this->access_token_expire_time = $this->account->access_token_expire_time;

        if (!$this->token) {
            throw new CHttpException(500, '请先配置token属性!');
        }


        /*if (isset($_GET)) {
            foreach ($_GET as $k1 => $v1) {
                if (property_exists($this, $k1)) {
                    $this->$k1 = $v1;
                }
            }
        }*/


        if (isset($_GET['echostr']) && $_GET['echostr']) {
            $this->log('微信服务器发来的请求信息');
            $this->log($_GET);
            $this->valid();

            #请求通过后，设置开启用户状态
            if ($this->account->status == 'N') {
                $this->account->status = 'Y';
                $this->account->save();
            }

            Yii::app()->end();
        }
    }

    /**
     * valid
     *
     * @access public
     * @return void
     */
    public function valid()
    {
        $this->log('身份确认:' . $_GET['echostr']);
        if ($this->checkSignature()) {
            echo $_GET['echostr'];
        }
    }

    /**
     * checkSignature
     *
     * @access private
     * @return void
     */
    private function checkSignature()
    {
        $this->log('检查访问签名');
        $tmpArr = array($_GET['token'], $_GET['timestamp'], $_GET['nonce']);
        $this->log($tmpArr);

        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        $this->log('验证结果:' . $tmpStr . ',' . $_GET['signature']);

        return $tmpStr == $_GET['signature'];
    }


    /**
     * 获得用户发过来的消息（消息内容和消息类型）
     */
    public function init()
    {
        $postStr = empty($GLOBALS["HTTP_RAW_POST_DATA"]) ? '' : $GLOBALS["HTTP_RAW_POST_DATA"];

        $this->log('系统接收到腾讯服务器的请求数据：', $postStr);
        $this->log('系统接收到腾讯服务器的请求数据：', $postStr);
        $this->log('系统接收到腾讯服务器的请求数据：', $postStr);


        if (!empty($postStr)) {
            $this->log('XML格式转换');
            $this->msg = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->log($this->msg);
        } else {
            $this->log('腾讯服务器的请求数据为空');
            exit;
        }

        #更新 accessToken
        if ($this->appid && $this->appsecret) {
            $this->checkToken();
        }

        #用户处理
        if ($this->msg) {
            $this->saveWeixinUser();
        }
    }

    /**
     * 获取微信用户，如果不存在，先保存
     * @internal param \weixin_account_id $id
     * @return \CActiveRecord|\Weixin
     */
    public function saveWeixinUser()
    {
        $this->log('获取用户信息');
        $user = Weixin::model()->getUserByOpenId($this->msg->FromUserName);

        if (!$user) {
            #新用户
            $this->log('数据库中不存在用户' . $this->msg->FromUserName);
            $user = new Weixin();
            $user->setAttributes(array(
                    'open_id' => $this->msg->FromUserName,
                    'source' => $this->msg->ToUserName,
                    'weixin_account_id' => $this->account->id,
                    'status' => 'Y',
            ));

            if (!$user->save()) {
                $this->log('新订阅后写入用户表出错');
                $this->log($user->getErrors());
            } else {
                $this->log('新订阅后成功写入用户表');
            }
        } else {

            #再次订阅
            if ($user->status == 'N') {
                $this->new_subscribe = false;
                $user->setAttributes(array(
                        'status' => 'Y',
                ));
                if (!$user->save()) {
                    $this->log('再次订阅后写入用户表出错');
                    $this->log($user->getErrors());
                } else {
                    $this->log('再次订阅后成功写入用户表');
                }
            } else if ($user->status == 'F') {

            }

            $this->log('数据库中已存在用户' . $this->msg->FromUserName);
        }
        $this->user = $user;
    }


    /**
     * 获取AccessToken
     */
    public function getAccessToken()
    {
        $this->log('token过期，重新获取token');

        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
                'grant_type' => 'client_credential',
                'appid' => $this->appid,
                'secret' => $this->appsecret,
        );

        $html = json_decode($this->get($url, $data), true);

        if ($html['access_token']) {
            $this->setAccessToken($html['access_token'], time() + 6000);
            $this->log('获取token成功:' . $this->access_token);
        } else {
            $this->log('获取token失败');
        }
    }

    /**
     * 设置AccessToken
     * @param $access_token
     * @param $access_token_expire_time
     */
    public function setAccessToken($access_token, $access_token_expire_time)
    {
        $this->log('设置accessToken');

        $this->access_token = $access_token;
        $this->access_token_expire_time = $access_token_expire_time;
        $this->account->attributes = array(
                'access_token' => $access_token,
                'access_token_expire_time' => $access_token_expire_time,
        );

        if (!$this->account->save()) {
            $this->log('保存access_token出错');
            $this->log($this->account->getErrors());
        }
    }

    /**
     * 检查token是否过期
     */
    public function checkToken()
    {
        if (!$this->access_token || $this->access_token_expire_time < time()) {

            $this->getAccessToken();
        }

        $this->log('当前AccessToken信息' . $this->access_token);
    }

    public function resultParse($data)
    {

        $errorCode = array(
                '-1' => '系统繁忙',
                '0' => '请求成功',
                '40001' => '获取access_token时AppSecret错误，或者access_token无效',
                '40002' => '不合法的凭证类型',
                '40003' => '不合法的OpenID',
                '40004' => '不合法的媒体文件类型',
                '40005' => '不合法的文件类型',
                '40006' => '不合法的文件大小',
                '40007' => '不合法的媒体文件id',
                '40008' => '不合法的消息类型',
                '40009' => '不合法的图片文件大小',
                '40010' => '不合法的语音文件大小',
                '40011' => '不合法的视频文件大小',
                '40012' => '不合法的缩略图文件大小',
                '40013' => '不合法的APPID',
                '40014' => '不合法的access_token',
                '40015' => '不合法的菜单类型',
                '40016' => '不合法的按钮个数',
                '40017' => '不合法的按钮个数',
                '40018' => '不合法的按钮名字长度',
                '40019' => '不合法的按钮KEY长度',
                '40020' => '不合法的按钮URL长度',
                '40021' => '不合法的菜单版本号',
                '40022' => '不合法的子菜单级数',
                '40023' => '不合法的子菜单按钮个数',
                '40024' => '不合法的子菜单按钮类型',
                '40025' => '不合法的子菜单按钮名字长度',
                '40026' => '不合法的子菜单按钮KEY长度',
                '40027' => '不合法的子菜单按钮URL长度',
                '40028' => '不合法的自定义菜单使用用户',
                '40029' => '不合法的oauth_code',
                '40030' => '不合法的refresh_token',
                '40031' => '不合法的openid列表',
                '40032' => '不合法的openid列表长度',
                '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
                '40035' => '不合法的参数',
                '40038' => '不合法的请求格式',
                '40039' => '不合法的URL长度',
                '40050' => '不合法的分组id',
                '40051' => '分组名字不合法',
                '41001' => '缺少access_token参数',
                '41002' => '缺少appid参数',
                '41003' => '缺少refresh_token参数',
                '41004' => '缺少secret参数',
                '41005' => '缺少多媒体文件数据',
                '41006' => '缺少media_id参数',
                '41007' => '缺少子菜单数据',
                '41008' => '缺少oauth code',
                '41009' => '缺少openid',
                '42001' => 'access_token超时',
                '42002' => 'refresh_token超时',
                '42003' => 'oauth_code超时',
                '43001' => '需要GET请求',
                '43002' => '需要POST请求',
                '43003' => '需要HTTPS请求',
                '43004' => '需要接收者关注',
                '43005' => '需要好友关系',
                '44001' => '多媒体文件为空',
                '44002' => 'POST的数据包为空',
                '44003' => '图文消息内容为空',
                '44004' => '文本消息内容为空',
                '45001' => '多媒体文件大小超过限制',
                '45002' => '消息内容超过限制',
                '45003' => '标题字段超过限制',
                '45004' => '描述字段超过限制',
                '45005' => '链接字段超过限制',
                '45006' => '图片链接字段超过限制',
                '45007' => '语音播放时间超过限制',
                '45008' => '图文消息超过限制',
                '45009' => '接口调用超过限制',
                '45010' => '创建菜单个数超过限制',
                '45015' => '回复时间超过限制',
                '45016' => '系统分组，不允许修改',
                '45017' => '分组名字过长',
                '45018' => '分组数量超过上限',
                '46001' => '不存在媒体数据',
                '46002' => '不存在的菜单版本',
                '46003' => '不存在的菜单数据',
                '46004' => '不存在的用户',
                '47001' => '解析JSON/XML内容错误',
                '48001' => 'api功能未授权',
                '50001' => '用户未授权该api',
        );

        $error = json_decode($data, true);


        if ($error && isset($error['errcode']) && $error['errcode']) {

            $error['errmsg'] = $errorCode[$error['errcode']];
            $result = $error;
            if ($error['errcode'] == 40001) {
                $this->log('已重新生成accessToken');
                $this->getAccessToken();
            }

        } else {
            $result = array(
                    'errcode' => 0,
                    'errmsg' => '操作成功',
                    'data' => $error,
            );
            //$this->log($result);
        }

        return $result;
    }

    /**
     * makeEvent
     *
     * @access public
     * @return void
     */
    public function makeEvent()
    {

    }

    /**
     * 创建菜单
     * @param $menus
     * @return mixed
     */
    public function createMenu($menus)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->access_token;
        $result = $this->post($url, $menus);
        return $this->resultParse($result);
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function deleteMenu()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=" . $this->access_token;
        $result = $this->get($url);
        return $this->resultParse($result);
    }

    public function getSubscribers($next_openid = null)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get";
        $data = array(
                'access_token' => $this->access_token,
                'next_openid' => $next_openid,
        );

        $result = $this->get($url, $data);
        return $this->resultParse($result);

    }


    /**
     * 获取用户资料
     * @param $openid
     * @return array|mixed
     */
    public function getSubscriberInfo($openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info";
        $data = array(
                'openid' => $openid,
                'access_token' => $this->access_token,
        );

        $result = $this->get($url, $data);
        return $this->resultParse($result);

    }

    /**获取分组
     * @return mixed
     */
    public function getGroup()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/groups/get';
        $data = array(
                'access_token' => $this->access_token,
        );
        $result = $this->get($url, $data);
        $this->log($result);

        return $this->resultParse($result);
    }

    /**
     * 创建分组
     * @param $name
     * @return mixed
     */
    public function createGroup($name)
    {
        $data = array(
                'group' => array(
                        'name' => $name
                ),
        );

        $result = $this->post('https://api.weixin.qq.com/cgi-bin/groups/create?access_token=' . $this->access_token, $data);
        return $this->resultParse($result);
    }

    /**
     * 更新分组
     * @param $id
     * @param $name
     * @return mixed
     */
    public function updateGroup($id, $name)
    {
        $data = array(
                'group' => array(
                        'id' => $id,
                        'name' => $name,
                ),
        );

        $result = $this->post('https://api.weixin.qq.com/cgi-bin/groups/update?access_token=' . $this->access_token, $data);
        return $this->resultParse($result);
    }

    /**
     * 移动分组
     * @param $open_id
     * @param $group_id
     * @return mixed
     */
    public function moveGroup($open_id, $group_id)
    {
        $data = array(
                'openid' => $open_id,
                'to_groupid' => $group_id,
        );

        $result = $this->post('https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $this->access_token, $data);
        return $this->resultParse($result);
    }

    /**
     * 下载资源
     * @param $media_id
     * @param $type
     * @param $weixin_id
     * @return mixed
     */
    public function downloadMedia($media_id, $type, $weixin_id)
    {
        //print_r($this->access_token);exit;
        $result = $this->get('http://file.api.weixin.qq.com/cgi-bin/media/get', array(
                'access_token' => $this->access_token,
                'media_id' => $media_id,
        ), true);

        $this->log('下载多媒体：' . $media_id);
        $this->log($result);
        //var_dump($result);

        if ($result && isset($result['type']) && $result['type'] && isset($result['data']) && $result['data']) {
            if ($weixin_media_id = $this->localizationMedia($type, $media_id, $result['data'], $result['type'], $weixin_id)) {
                $result['weixin_media_id'] = $weixin_media_id;
            }
            return $result;
        } else {
            Yii::log(var_export($result), 'error', 'weixinhelper.downloadMedia');
        }
    }

    /**
     * 本地化数据
     * @param $type
     * @param $media_id
     * @param $data
     * @param $ext
     * @param $weixin_id
     * @return mixed|string
     */
    public function localizationMedia($type, $media_id, $data, $ext, $weixin_id)
    {

        $this->log('本地化多媒体：' . $media_id);

        $monthDir = date('Ym');
        $dayDir = date('d');
        $filename = md5($media_id) . '.' . $ext;

        $uploadPath = dirname(Yii::app()->BasePath);
        $attachmemntPath = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'weixin_media' . DIRECTORY_SEPARATOR . $monthDir . DIRECTORY_SEPARATOR . $dayDir . DIRECTORY_SEPARATOR;


        if (!is_dir($uploadPath . $attachmemntPath) || !file_exists($uploadPath . $attachmemntPath)) {
            mkdir($uploadPath . $attachmemntPath, 0777, true);
        } else if (!is_writable($uploadPath . $attachmemntPath)) {
            chmod($uploadPath . $attachmemntPath, 0777);
        }


        $path = $attachmemntPath . $filename;

        if (!file_exists($uploadPath . $path)) {
            $this->log('本地不存在，下载中。');
            if (!file_put_contents($uploadPath . $path, $data)) {
                $this->log('下载失败');
                $path = '';
            } else {
                $this->log('下载成功：' . $media_id);


                $model = WeixinMedia::model()->find(
                        'media_id=:media_id', array(
                                ':media_id' => $media_id
                        )
                );

                if (!$model) {
                    $model = new WeixinMedia();
                    $model->setAttributes(array(
                            'media_id' => $media_id,
                    ));

                }
                $model->setAttributes(array(
                        'type' => $type,
                        'recognition' => $this->msg->Recognition,
                        'status' => 'N',
                        'path' => $path,
                        'weixin_id' => $weixin_id,
                ));

                if ($model->save()) {
                    $this->log('保存到WeixinMedia');
                    return $model->id;
                } else {
                    $this->log($model->getErrors());
                }
            }
        } else {

            $model = WeixinMedia::model()->find('media_id=:media_id',
                    array(
                            ':media_id' => $media_id
                    )
            );

            if ($model) {
                $this->log('本地存在，直接返回。');
                return $model->id;
            }
        }
    }

    /**
     * 上传资源
     * @param $type 分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @param $file
     * @return mixed
     */
    public function uploadMedia($type, $file)
    {
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->access_token . '&type=' . $type;
        $data = array(
                'media' => '@' . dirname(Yii::app()->basePath) . $file,
        );

        //print_r($data);
        $result = $this->post($url, $data);

        /*var_dump($result);
        exit;*/
        $result = $this->resultParse($result);

        /*print_r($result);
        exit;*/

        if ($result && isset($result['errcode']) && $result['errcode'] == 0) {
            #Array ( [errcode] => 0 [errmsg] => 操作成功 [data] => Array ( [type] => voice [media_id] => HULmA5edIB979oRx3jUZnF7OIyGudWbirEtvf-S9ghLEbA7rW-WWzqsTIraBR-5e [created_at] => 1402647425 ) )
            return $result['data'];
        } else {
            //print_r($result);
            $this->log('文件“' . $file . '”上传失败:' . $result['errmsg']);
        }
    }


    /**
     * 获取永久二维码，scene_id 不得大于100000
     * @param $scene_id
     * @return array|mixed
     */
    public function getQRTicket($scene_id)
    {

        if ($scene_id > 100000) {
            $scene_id = $scene_id % 100000;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$this->access_token}";
        $data = array(
                'action_name' => 'QR_LIMIT_SCENE',
                'action_info' => array(
                        'scene' => array(
                                'scene_id' => $scene_id,
                        )
                ),
        );

        $result = $this->post($url, $data);
        return $this->resultParse($result);

    }

    /**
     * 获取临时二维码
     * @param $scene_id
     * @param int $expire_seconds 有效期
     * @return array|mixed
     */
    public function getQRTicketTemporary($scene_id, $expire_seconds = 1800)
    {
        $expire_seconds = max($expire_seconds, 1800);
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$this->access_token}";
        $data = array(
                'expire_seconds' => $expire_seconds,
                'action_name' => 'QR_SCENE',
                'action_info' => array(
                        'scene' => array(
                                'scene_id' => $scene_id,
                        )
                ),
        );

        $result = $this->post($url, $data);
        return $this->resultParse($result);

    }

    /**
     * 通过ticket获取二维码图片
     * @param $ticket
     * @return string
     */
    public function getQRCode($ticket)
    {
        $ticket = urlencode($ticket);
        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
    }

    /**
     * 创建二维码
     */
    public function createQRCode()
    {
        if (!$this->user->qrcode_ticket) {
            $this->log('获取微信二维码');
            $getQRTicket = false;
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $model = WeixinInvitationCode::model()->find('weixin_id=:weixin_id', array(
                        ':weixin_id' => $this->user->id,
                ));

                #找到闲置，则重置时间，否则先查看1000天内无效，找到则修改，找不到新建一个。
                if ($model) {
                    $this->log('已有二维码');
                    if (!$model->ticket) {
                        $result = $this->getQRTicket($model->id);
                        if ($result && $result['errcode'] == 0) {
                            $model->setAttributes(array(
                                    'ticket' => $result['data']['ticket'],
                            ));
                            $this->log('替换过期二维码成功');
                        }
                    }

                    $model->setAttributes(array(
                            'dateline' => time(),
                    ));

                } else {
                    $dateline = time() - 86400 * 365; #360天内无扫描记录
                    $model = WeixinInvitationCode::model()->find(
                            array(
                                    'condition' => 'scan_count=0 AND dateline<:dateline AND ticket IS NOT NULL',
                                    'params' => array(
                                            ':dateline' => $dateline,
                                    ),
                                    'order' => 'id ASC',
                            )
                    );

                    #存在则修改weixin_id，不存在重新创建一个
                    if ($model) {
                        $this->log('找到闲置二维码，准备替换');
                        $model->setAttributes(array(
                                'weixin_id' => $this->user->id,
                                'dateline' => DATELINE,
                        ));
                    } else {
                        $getQRTicket = true;
                        //$code = strtolower(substr(md5($this->user->id), 0, 4));

                        $model = new WeixinInvitationCode();
                        $model->setAttributes(array(
                                'weixin_id' => $this->user->id,
                            //'code' => $code,
                                'dateline' => DATELINE,
                        ));
                        $this->log('创建二维码');
                    }


                }

                if (!$model->save()) {
                    throw new HttpException(500, serialize($model->getErrors()));
                } else {
                    $this->log('保存成功');
                }

                #先保存model，得到id,再获取微信二维码
                if ($getQRTicket) {
                    $result = $this->getQRTicket($model->id);
                    if ($result && $result['errcode'] == 0) {
                        $model->setAttributes(array(
                                'ticket' => $result['data']['ticket'],
                        ));
                        if (!$model->save()) {
                            throw new HttpException(500, serialize($model->getErrors()));
                        }
                    }
                }

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->log('创建微信二维码失败');
            }

            //$this->user->refresh();
            $this->user->qrcode_ticket = $model->ticket;
            //$this->user->save();
            return $model->ticket;
        }
    }

    /**
     * @param $code
     * @return mixed
     * 会得到　$oauth2_token
     */
    public function oauth2($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $data = array(
                'appid' => $this->appid,
                'secret' => $this->appsecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
        );
        $result = $this->get($url, $data);
        return $this->resultParse($result);
    }

    /**
     * 通过oauth获取用户资料
     * @param $oauth2_token
     * @param $open_id
     * @return mixed
     */
    public function getUserInfo($oauth2_token, $open_id)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $oauth2_token . '&openid=' . $open_id;
        $result = $this->get($url);
        return $this->resultParse($result);
    }


    public function get($url, $data = array(), $header = false)
    {

        if ($data && is_array($data)) {
            $query = array();
            foreach ($data as $key => $value) {
                $query[] = $key . '=' . $value;
            }

            $url = $url . '?' . implode('&', $query);
        }

        $this->log('Get请求地址：' . $url);


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            $this->log('PHP CURL Error: ' . curl_error($curl)); //捕抓异常
        }

        if ($header) {
            $res = curl_getinfo($curl);

            $this->log('请求的头部信息：');
            $this->log($res);
        }

        curl_close($curl);

        if ($header) {
            $result = array(
                    'type' => $this->getExtByContentType($res['content_type']),
                    'data' => $tmpInfo,
            );
        } else {
            $result = $tmpInfo;
        }
        return $result;
    }


    function post($url, $data)
    {

        $this->log('Post请求地址：' . $url);
        $this->log('Post请求数据：');
        $this->log($data);

        #上传文件，不需要 encode
        if (!isset($data['media'])) {
            $data = OutputHelper::jsonEncode($data);
        }

        $this->log($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            $this->log('PHP CURL Error: ' . curl_error($curl)); //捕抓异常
        } else {
            //print_r(curl_getinfo($curl));
        }
        curl_close($curl);
        return $tmpInfo;
    }

    /**
     * 生成消息体
     * @param $message
     * @return null
     */
    public function makeMessage($message)
    {
        $result = null;

        if ($message && $message['type']) {
            $method = 'send' . strtoupper($message['type']);
            if (method_exists($this, $method)) {
                $result = $this->{$method}($message['data']);
            } else {
                $this->log('消息发送方法“' . $method . '”不存在');
            }
        } else {
            $this->log('生成“' . $message['type'] . '”消息时，消息结构有误');
            $this->log($message);
        }
        return $result;
    }

    /**
     * 回复文本消息
     *
     * @param string $text
     * @access public
     * @return void
     */
    public function sendText($text = '')
    {
        $createTime = time();
        $funcFlag = $this->flag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
            <CreateTime>{$createTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        return sprintf($textTpl, $text, $funcFlag);
    }

    /**
     * 根据数组参数回复图文消息
     *
     * @param array $newsData
     * @access public
     * @return void
     */
    public function sendNews($newsData = array())
    {
        $createTime = time();
        $funcFlag = $this->flag ? 1 : 0;
        $newTplHeader = "<xml>
            <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
            <CreateTime>{$createTime}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>%s</ArticleCount><Articles>";
        $newTplItem = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $newTplFoot = "</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        $content = '';
        $itemsCount = count($newsData);
        #微信公众平台图文回复的消息一次最多10条
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;
        if ($itemsCount) {
            foreach ($newsData as $key => $item) {
                if ($key <= 9) {
                    $content .= sprintf($newTplItem, $item['title'], $item['description'], $item['picurl'], $item['url']);
                }
            }
        }
        $header = sprintf($newTplHeader, $itemsCount);
        $footer = sprintf($newTplFoot, $funcFlag);
        return $header . $content . $footer;
    }


    public function sendMusic($data)
    {
        #发送音乐信息时，需要将图片上传至微信

        $createTime = time();
        $tpl = "<xml>
        <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
        <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
        <CreateTime>{$createTime}</CreateTime>
        <MsgType><![CDATA[music]]></MsgType>
        <Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        </Music>
        </xml>";

        return sprintf($tpl, $data['title'], $data['description'], $data['url'], $data['url'], $data['mediaid']);
    }

    public function sendVoice($data)
    {
        #发送语音

        $createTime = time();
        $tpl = "<xml>
        <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
        <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
        <CreateTime>{$createTime}</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        <Voice>
        <MediaId><![CDATA[%s]]></MediaId>
        </Voice>
        </xml>";

        return sprintf($tpl, $data);
    }

    /**
     * reply
     * 被动响应式消息
     * @access public
     * @return void
     */
    public function response()
    {
        $message = $this->makeMessage($this->message);
        $this->log('响应数据：');
        $this->log($message);
        echo $message;
    }


    /**
     * 发送主动式消息
     * @param $data
     * @return array|mixed
     */
    public function sendMessage($data)
    {
        //print_r($data);exit;
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->access_token;
        $result = $this->post($url, $data);
        return $this->resultParse($result);
    }

    public function setFlag($value)
    {
        $this->flag = $value ? true : false;
    }


    /**
     * 消息打印
     */
    public function log()
    {
        if ($this->debug) {
            $filepath = Yii::getPathOfAlias('application') . '/runtime/weixinlog_' . $this->account->id . '/';
            if (!file_exists($filepath)) {
                mkdir($filepath, 0777, true);
            }

            $arg_list = func_get_args();
            foreach ($arg_list as $key => $value) {
                file_put_contents(
                        $filepath . date('Y-m-d') . '.txt',
                        "+++++++++++++++++++++++++++++++++++++++++++\r\n" .
                        date('Y-m-d H:i:s', time()) .
                        "\r\n+++++++++++++++++++++++++++++++++++++++++++\r\n" .
                        var_export($value, true) . "\n\r",
                        FILE_APPEND
                );
            }
        }
    }

    public static function debug($log)
    {

        $filepath = Yii::getPathOfAlias('application') . '/runtime/weixinlog/';
        if (!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        file_put_contents(
                $filepath . date('Y-m-d') . '.txt',
                "+++++++++++++++++++++++++++++++++++++++++++\r\n" .
                date('Y-m-d H:i:s', time()) .
                "\r\n+++++++++++++++++++++++++++++++++++++++++++\r\n" .
                var_export($log, true) . "\n\r",
                FILE_APPEND
        );

    }

#百度API相关

    public function getGeoAddress($location_X, $location_Y)
    {
        $result = array();

        $url = 'http://api.map.baidu.com/geocoder/v2/';
        $data = array(
                'ak' => $this->account->baidu_ak,
                'pois' => 0,
                'output' => 'json',
                'location' => $location_X . ',' . $location_Y,
        );

        $this->log('百度API地址查询：' . $url);
        $result = $this->get($url, $data);
        $location = json_decode($result, false);

        //$this->log($location);

        if ($location->status == 'OK') {
            $result = $location->result;
        }

        return $result;
    }


    public function error($type = 'notice')
    {
        $model = new WeixinLog();
        $content = $this->msg->MsgType != 'text' ? $this->msg->MsgType . '|' . $this->msg->Event . '|' . $this->msg->EventKey : 'text' . '|' . $this->msg->Content;
        $model->attributes = array(
                'type' => $type,
                'weixin_id' => $this->weixin->user->id,
                'weixin_account_id' => $this->weixin->account->id,
                'content' => $content,
                'execute_time' => $this->execute_time,
                'dateline' => time(),
        );
        $model->save();
    }

    public function getExtByContentType($type)
    {
        $array = array(
                'image/gif' => 'gif',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'audio/amr' => 'amr',
        );

        if (isset($array[$type])) {
            return $array[$type];
        } else {
            Yii::log('WeixinHelper getExtByContentType can\'t find ext');
            return false;
        }
    }


    /**
     * 生成订单package字符串
     * @param string $out_trade_no 必填，商户系统内部的订单号,32个字符内,确保在商户系统唯一
     * @param string $body 必填，商品描述,128 字节以下
     * @param int $total_fee 必填，订单总金额,单位为分
     * @param string $notify_url 必填，支付完成通知回调接口，255 字节以内
     * @param string $spbill_create_ip 必填，用户终端IP，IPV4字串，15字节内
     * @param int $fee_type 必填，现金支付币种，默认1:人民币
     * @param string $bank_type 必填，银行通道类型,默认WX
     * @param string $input_charset 必填，传入参数字符编码，默认UTF-8，取值有UTF-8和GBK
     * @param string $time_start 交易起始时间,订单生成时间,格式yyyyMMddHHmmss
     * @param string $time_expire 交易结束时间,也是订单失效时间
     * @param int|string $transport_fee 物流费用,单位为分
     * @param int|string $product_fee 商品费用,单位为分,必须保证 transport_fee + product_fee=total_fee
     * @param string $goods_tag 商品标记,优惠券时可能用到
     * @param string $attach 附加数据，notify接口原样返回
     * @return string
     */
    public function createPackage($out_trade_no, $body, $total_fee, $notify_url, $spbill_create_ip, $fee_type = 1, $bank_type = "WX", $input_charset = "UTF-8", $time_start = "", $time_expire = "", $transport_fee = "", $product_fee = "", $goods_tag = "", $attach = "")
    {
        $arrdata = array("bank_type" => $bank_type, "body" => $body, "partner" => $this->partnerid, "out_trade_no" => $out_trade_no, "total_fee" => $total_fee, "fee_type" => $fee_type, "notify_url" => $notify_url, "spbill_create_ip" => $spbill_create_ip, "input_charset" => $input_charset);
        if ($time_start) $arrdata['time_start'] = $time_start;
        if ($time_expire) $arrdata['time_expire'] = $time_expire;
        if ($transport_fee) $arrdata['transport_fee'] = $transport_fee;
        if ($product_fee) $arrdata['product_fee'] = $product_fee;
        if ($goods_tag) $arrdata['goods_tag'] = $goods_tag;
        if ($attach) $arrdata['attach'] = $attach;
        ksort($arrdata);
        $paramstring = "";
        foreach ($arrdata as $key => $value) {
            if (strlen($paramstring) == 0)
                $paramstring .= $key . "=" . $value;
            else
                $paramstring .= "&" . $key . "=" . $value;
        }
        $stringSignTemp = $paramstring . "&key=" . $this->partnerkey;
        $signValue = strtoupper(md5($stringSignTemp));
        $package = http_build_query($arrdata) . "&sign=" . $signValue;
        return $package;
    }

    /**
     * 支付签名(paySign)生成方法
     * @param string $package 订单详情字串
     * @param string $timeStamp 当前时间戳（需与JS输出的一致）
     * @param string $nonceStr 随机串（需与JS输出的一致）
     * @return string 返回签名字串
     */
    public function getPaySign($package, $timeStamp, $nonceStr)
    {
        $arrdata = array("appid" => $this->appid, "timestamp" => $timeStamp, "noncestr" => $nonceStr, "package" => $package, "appkey" => $this->paysignkey);
        $paySign = $this->getSignature($arrdata);
        return $paySign;
    }

    /**
     * 回调通知签名验证
     * @param array|string $orderxml 返回的orderXml的数组表示，留空则自动从post数据获取
     * @return boolean
     */
    public function checkOrderSignature($orderxml = '')
    {
        if (!$orderxml) {
            $postStr = file_get_contents("php://input");
            if (!empty($postStr)) {
                $orderxml = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            } else return false;
        }
        $arrdata = array('appid' => $orderxml['AppId'], 'appkey' => $this->paysignkey, 'timestamp' => $orderxml['TimeStamp'], 'noncestr' => $orderxml['NonceStr'], 'openid' => $orderxml['OpenId'], 'issubscribe' => $orderxml['IsSubscribe']);
        $paySign = $this->getSignature($arrdata);
        if ($paySign != $orderxml['AppSignature']) return false;
        return true;
    }

    /**
     * 发货通知
     * @param string $open_id 用户open_id
     * @param string $transid 交易单号
     * @param string $out_trade_no 第三方订单号
     * @param int $status 0:发货失败；1:已发货
     * @param string $msg 失败原因
     * @return boolean|array
     */
    public function sendPayDeliverNotify($open_id, $transid, $out_trade_no, $status = 1, $msg = 'ok')
    {
        if (!$this->access_token && !$this->checkAuth()) return false;
        $postdata = array(
                "appid" => $this->appid,
                "appkey" => $this->paysignkey,
                "openid" => $open_id,
                "transid" => strval($transid),
                "out_trade_no" => strval($out_trade_no),
                "deliver_timestamp" => strval(time()),
                "deliver_status" => strval($status),
                "deliver_msg" => $msg,
        );
        $postdata['app_signature'] = $this->getSignature($postdata);
        $postdata['sign_method'] = 'sha1';
        unset($postdata['appkey']);
        $result = $this->http_post(self::PAY_DELIVERNOTIFY . 'access_token=' . $this->access_token, self::json_encode($postdata));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /*
     * 查询订单信息
     * @param string $out_trade_no 订单号
     * @return boolean|array
     */
    public function getPayOrder($out_trade_no)
    {
        if (!$this->access_token && !$this->checkAuth()) return false;
        $sign = strtoupper(md5("out_trade_no=$out_trade_no&partner={$this->partnerid}&key={$this->partnerkey}"));
        $postdata = array(
                "appid" => $this->appid,
                "appkey" => $this->paysignkey,
                "package" => "out_trade_no=$out_trade_no&partner={$this->partnerid}&sign=$sign",
                "timestamp" => strval(time()),
        );
        $postdata['app_signature'] = $this->getSignature($postdata);
        $postdata['sign_method'] = 'sha1';
        unset($postdata['appkey']);
        $result = $this->http_post(self::PAY_ORDERQUERY . 'access_token=' . $this->access_token, self::json_encode($postdata));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'] . json_encode($postdata);
                return false;
            }
            return $json["order_info"];
        }
        return false;
    }

    /**
     * 获取收货地址JS的签名
     * @param string $url
     * @param int $timeStamp
     * @param string $nonceStr
     * @param string $user_token
     * @internal param string $appId
     * @return Ambigous <boolean, string>
     */
    public function getAddrSign($url, $timeStamp, $nonceStr, $user_token = '')
    {
        if (!$user_token) $user_token = $this->user_token;
        if (!$user_token) {
            $this->errMsg = 'no user access token found!';
            return false;
        }
        $url = htmlspecialchars_decode($url);
        $arrdata = array(
                'appid' => $this->appid,
                'url' => $url,
                'timestamp' => strval($timeStamp),
                'noncestr' => $nonceStr,
                'accesstoken' => $user_token
        );
        return $this->getSignature($arrdata);
    }

}