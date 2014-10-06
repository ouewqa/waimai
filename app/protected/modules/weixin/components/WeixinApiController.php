<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午5:18
 * To change this template use File | Settings | File Templates.
 */
class WeixinApiController extends Controller
{
    public $weixin;

    /**
     * 微信关注事件　
     */
    public function weixinSubscribeEvent()
    {
        #检查是否存在特殊指令
        $material = $this->findKeyword('followResponse');

        if ($material) {
            $this->makeMaterialDataFormat($material);
        }

        #有高级接口的情况，保存用户其他信息
        if ($this->weixin->account->advanced_interface == 'Y') {
            $this->weixin->log('高级接口，执行用户基本信息获取，open_id', $this->weixin->user->open_id);
            $result = $this->weixin->getSubscriberInfo($this->weixin->user->open_id);

            $this->weixin->log($result);

            if ($result['errcode'] === 0) {

                try {
                    $this->weixin->user->setAttributes(array(
                            'nickname' => ($this->weixin->user->nickname && $this->weixin->user->nickname != '无名大侠') ? $this->weixin->user->nickname : $result['data']['nickname'],
                            'sex' => $result['data']['sex'],
                            'language' => $result['data']['language'],
                            'city' => $result['data']['city'],
                            'province' => $result['data']['province'],
                            'country' => $result['data']['country'],
                            'headimgurl' => $result['data']['headimgurl'],
                            'last_update_time' => time(),
                    ));

                } catch (Exception $e) {
                    $this->weixin->user->setAttributes(array(
                            'nickname' => '无名大侠',
                            'sex' => $result['data']['sex'],
                            'language' => $result['data']['language'],
                            'city' => $result['data']['city'],
                            'province' => $result['data']['province'],
                            'country' => $result['data']['country'],
                            'headimgurl' => $result['data']['headimgurl'],
                            'last_update_time' => time(),
                    ));
                }
                $this->weixin->log('最终保存用户的数据', $this->weixin->user->getAttributes());
            }
        }


        #判断是否由带场景值
        if ($this->weixin->msg->EventKey) {
            $scene_id = substr($this->weixin->msg->EventKey, 8);
            $this->weixin->scene_id = $scene_id;

            if ($this->weixin->scene_id) {
                $this->weixin->log('场景值为:' . $this->weixin->scene_id);
                #邀请好友注册
                if ($this->weixin->scene_id <= 100000) {
                    $this->weixin->log('二维码邀请好友');
                } else {
                    $this->weixin->log('临时二维码');
                }
            }
        }
    }

    /**
     * 微信扫描事件
     */
    public function weixinScanEvent()
    {
        #扫描永久的二维码
        if ($this->weixin->msg->EventKey <= 100000) {
            $model = ShopOrder::model()->with('usedAddresses')->findBySceneId($this->weixin->msg->EventKey);
            if ($model) {

                #如果是管理员,group_id=2，则为发货，如果是用户。则为收货

                $this->weixin->log('微信用户组：' . $this->weixin->user->weixin_group_id);
                if ($this->weixin->user->weixin_group_id == 2) {

                    if ($model->status == 10) {
                        $message = array(
                                'type' => 'text',
                                'data' => '订单号：' . $model->id . '，已经设置过派送中了',
                        );

                        #todo 发货通知用户，短信
                        if ($this->weixin->account->notify_customer_method) {
                            switch ($this->weixin->account->notify_customer_method) {
                                case 'sms' :
                                    try {
                                        $admin = Admin::model()->findByPk($this->weixin->account->admin_id);
                                        $sms = new SMS($model->usedAddresses->mobile, $admin);
                                        if ($sms) {
                                            $sms->send('订单号：' . $model->id . '，已经设置过派送中了', 'O');
                                        }
                                    } catch (Exception $e) {

                                    }
                                    break;

                                default:
                            }
                        }

                    } else {
                        $model->status = 10;
                        if ($model->save()) {
                            $message = array(
                                    'type' => 'text',
                                    'data' => '订单号：' . $model->id . '，状态设置为派送中！',
                            );
                        } else {
                            $message = array(
                                    'type' => 'text',
                                    'data' => '订单发货状态设置环节出错，请联系管理员，订单号：' . $model->id,
                            );
                        }
                    }


                } else {

                    if ($model->weixin_id != $this->weixin->user->id) {

                        $message = array(
                                'type' => 'text',
                                'data' => '该二维码绑定的订单（订单号：' . $model->id . '），不是你的订单。',
                        );

                    } else {
                        if ($model->status == 20 || $model->status == 21) {
                            $message = array(
                                    'type' => 'text',
                                    'data' => '本订单已完成，订单号：' . $model->id,
                            );
                        } else {

                            $model->status = 20;
                            if ($model->save()) {
                                $message = array(
                                        'type' => 'text',
                                        'data' => '恭喜，你已完成本次订餐，订单号：' . $model->id,
                                );
                            } else {
                                $message = array(
                                        'type' => 'text',
                                        'data' => '订单确认环节出错，请联系管理员，订单号：' . $model->id,
                                );
                            }
                        }
                    }


                }


                #todo　积分奖励
            } else {
                $message = array(
                        'type' => 'text',
                        'data' => '该二维码不存在或已过期，请联系管理员!',
                );
            }

        } else {
            $message = array(
                    'type' => 'text',
                    'data' => '该二维码为无效二维码，请联系管理员!',
            );
        }

        $this->weixin->message = $message;

    }

    /**
     * 事件响应
     */
    public function responseEvent()
    {


        $event = strtolower(strval($this->weixin->msg->Event));
        $eventKey = strval($this->weixin->msg->EventKey);

        $this->weixin->log('请求事件：', $event);
        $this->weixin->log('请求内容：', $eventKey);

        $this->weixin->message = array(
                'type' => 'text',
                'data' => '',
        );


        switch ($event) {

            case 'subscribe':
                $this->weixinSubscribeEvent();
                break;

            case 'unsubscribe':

                break;
            case 'click':
                $this->weixin->log('菜单中点击事件');
                $this->responseClick($eventKey);
                break;
            case 'location':
                //不返回任何消息

                break;
            case 'view':

                break;
            case 'scan':
                $this->weixinScanEvent();
                break;
            default:
        }

    }

    /**
     * 响应文本请求
     * @return array|null|void
     */
    public function responseText()
    {
        #检查是否存在特殊指令
        $material = $this->findKeyword();

        if ($material) {
            $this->makeMaterialDataFormat($material);
        }

        #更新最后请求时间
        $this->weixin->user->last_request_time = time();
    }

    /**
     * 构造素材数据格式
     * @param Material $material
     */
    public function makeMaterialDataFormat(Material $material)
    {
        switch ($material->type) {
            case 'N':
                $message = array(
                        'type' => 'news',
                        'data' => array(
                                array(
                                        'title' => $material->title,
                                        'description' => $material->description,
                                        'picurl' => ImageHelper::setFullPath($material->image),
                                        'url' => $this->makeMaterialUrlFormat($material->url),
                                )
                        )
                );
                break;
            case 'T':
                $message = array(
                        'type' => 'text',
                        'data' => strip_tags($material->content),
                );
                break;
        }

        $this->weixin->log('返回消息', $message);

        $this->weixin->message = $message;
    }


    public function makeMaterialUrlFormat($url)
    {
        $url = Yii::app()->createAbsoluteUrl($url, array(
                'open_id' => $this->weixin->user->open_id,
        ));
        return $url;
    }

    /**
     * 从素材库中查找关键字
     * @param null $keyword
     * @return CActiveRecord
     */
    public function findKeyword($keyword = null)
    {
        $keyword = $keyword ? $keyword : trim($this->weixin->msg->Content);
        $this->weixin->log('查找关键字：', $keyword);
        $model = Material::model()->find('weixin_account_id=:weixin_account_id AND keyword=:keyword', array(
                ':weixin_account_id' => $this->weixin->account->id,
                ':keyword' => $keyword,
        ));

        if (!$model && empty($model->content)) {
            $model = Material::model()->find('weixin_account_id=:weixin_account_id AND keyword=:keyword', array(
                    ':weixin_account_id' => $this->weixin->account->id,
                    ':keyword' => 'defaultResponse',
            ));
        }

        return $model;
    }

    /**
     * 默认回复
     */
    public function defaultResponse()
    {
        switch ($this->weixin->msg->MsgType) {
            case 'text':
                $message = array();
                break;

            case 'image':

                break;
            case 'voice':

                break;

            case 'location':

                break;

            default:
                $message = array();

        }

        $this->weixin->message = array(
                'type' => 'text',
                'data' => '您的留言我已收到。',
        );
    }

    /**
     * 响应图片事件
     * @return array
     */
    public function responseImage()
    {

        /*$scope = OutputHelper::getTodayScope();
        #查找今天内自己的订单
        $order = ShopOrder::model()->with('items')->find(
                array(
                        'condition' => 'weixin_id=:weixin_id AND dateline BETWEEN :begin AND :end',
                        'params' => array(
                                ':weixin_id' => $this->weixin->user->id,
                                ':begin' => $scope['begin'],
                                ':end' => $scope['end'],
                        ),
                        'order' => 'dateline DESC',
                )
        );

        if ($order) {
            foreach ($order->items as $key => $value) {

            }

        }*/

        $message = array(
                'type' => 'text',
                'data' => '平台已经收到你的图片',
        );

        return $message;
    }

    /**
     * 响应语音信息
     */
    public function responseVoice()
    {


    }

    /**
     * 响应地理位置
     * @return string
     */
    public function responseLocation()
    {

    }

    /**
     * 响应菜单中的点击事件
     * @param $key
     * @return mixed
     */
    public function responseClick($key)
    {
        $key = strtolower(substr($key, 1, count($key) - 2));

        $function = new WeixinMenuClick($this->weixin->user);
        if (method_exists($function, $key)) {
            $this->weixin->log('存在方法', $key);
            try {
                $this->weixin->message = $function->$key();
            } catch (CHttpException $e) {
                $this->weixin->log('点击事件执行出错', $e->getMessage());
            }

        } else {
            $this->weixin->log('不存在方法', $key);
            $this->weixin->message = array(
                    'type' => 'text',
                    'data' => "不存在{$key}这个方法，请联系管理员。",
            );
        }
    }

    /**
     * 匹配关键字的方式
     * @param $method 匹配方式
     * @param $pattern 　查找什么
     * @param $content 　在哪里查找
     * @return bool
     */
    public function matchCommand($method, $pattern, $content)
    {
        $result = false;
        switch ($method) {
            #完全相等
            case 'precise':
                $result = $pattern == $content;
                break;
            #包含
            case 'fuzzy':

                $result = (strpos($content, strval($pattern)) === false) ? false : true;
                break;
            #正则匹配
            case 'regular':
                $result = preg_match($pattern, $content);
                break;

            default:
                $result = false;
        }

        $this->weixin->log('在[' . $content . ']中查找[' . $pattern . '],匹配方式:' . $method . ',结果：' . var_export($result, true));

        return $result;
    }

    /**
     * 搜索流程
     */
    public function executeSearch()
    {
        #todo 搜索事项
        return;
    }

    /**
     * 资源本地化
     */
    public function saveSource()
    {
        $source = '';
        if ($this->weixin->msg->MediaId) {
            $source = $this->weixin->downloadMedia($this->weixin->msg->MediaId);

        }

        return $source;
    }

    /**
     * 保存用户消息
     */
    public function saveMessage()
    {

        $do = 'save' . ucfirst($this->weixin->msg->MsgType) . 'Message';
        $data = array();

        $this->weixin->log('准备保存响应消息动作' . $do);

        try {
            #获取各类型消息内容
            $message = $this->{$do}();
        } catch (Exception $e) {
            $this->weixin->log($do . '　保存消息函数出错!', $e->getMessage());
        }

        if ($message) {
            $dataBase = array(
                    'weixin_account_id' => $this->weixin->account->id,
                    'weixin_id' => $this->weixin->user->id,
                    'type' => strtolower($this->weixin->msg->MsgType),
                    'io' => 'I',
                    'status' => 'N',
                    'dateline' => $this->weixin->msg->CreateTime,
            );

            $attributes = CMap::mergeArray(
                    $dataBase,
                    $message
            );
            $model = new WeixinMessage();
            $model->attributes = $attributes;
            if (!$model->save()) {
                $this->weixin->log($model->getErrors());
            } else {
                $this->weixin->log('微信消息保存成功');
                $this->weixin->log($attributes);
            }
        }
    }

    /**
     * 保存文本消息
     * @return array
     */
    public function saveTextMessage()
    {
        return array(
                'message' => $this->weixin->msg->Content,
        );
    }

    /**
     * 保存语音消息
     */
    public function saveVoiceMessage()
    {
        if ($this->weixin->msg->Recognition) {
            return array(
                    'message' => $this->weixin->msg->Recognition,
            );
        }
    }

    /**
     * 保存图片消息
     */
    public function saveImageMessage()
    {
        return array(
                'message' => $this->weixin->msg->PicUrl,
        );
    }

    /**
     * 保存地理位置消息
     */
    public function saveLocationMessage()
    {
        $this->weixin->log('准备保存用户地理位置');

        #每隔 1 天保存一次地理位置

        if (time() - $this->weixin->user->last_location_time > 86400 * 1) {

            #设置最后保存地理位置时间
            $this->weixin->user->last_location_time = time();

            #获取位置记录
            $model = WeixinLocation::model()->findMyLastLocation($this->weixin->user->id);

            if (!$model) {
                $model = new WeixinLocation();
                $model->weixin_id = $this->weixin->user->id;
                $model->weixin_account_id = $this->weixin->account->id;
            }

            //$this->weixin->log($url);
            //$this->weixin->log($location);

            $data = array(
                    'latitude' => $this->weixin->msg->Location_X ? $this->weixin->msg->Location_X : $this->weixin->msg->Latitude,
                    'longitude' => $this->weixin->msg->Location_Y ? $this->weixin->msg->Location_Y : $this->weixin->msg->Longitude,
                    'precision' => $this->weixin->msg->Scale ? $this->weixin->msg->Scale : $this->weixin->msg->Precision,
                    'address' => $this->weixin->msg->Label ? $this->weixin->msg->Label : '',
                    'dateline' => $this->weixin->msg->CreateTime,
            );

            //$this->weixin->log($data);


            $location = $this->weixin->getGeoAddress($data['latitude'], $data['longitude']);
            if ($location) {
                $this->weixin->log('百度获取的地理数据');
                $this->weixin->log($location);

                $data = CMap::mergeArray($data, array(
                        'address' => $location->formatted_address,
                        'province' => $location->addressComponent->province,
                        'city' => $location->addressComponent->city,
                        'district' => $location->addressComponent->district,
                        'street' => $location->addressComponent->street,
                        'street_number' => $location->addressComponent->street_number,
                        'query_address' => $location->addressComponent->province . $location->addressComponent->city . $location->addressComponent->district,
                ));
            } else {
                $this->weixin->log('百度获取地理数据失败');
            }


            $this->weixin->log('保存地理位置至数据库');
            $this->weixin->log($data);

            $model->setAttributes($data);

            if (!$model->save()) {
                $this->weixin->log('地理位置保存出错。');
                $this->weixin->log($model->getErrors());
            } else {
                $this->weixin->log('地理位置保存成功。');
            }
        } else {
            $this->weixin->log('距最后一次保存地理位置未超过3小时，不保存');
        }


    }

    /**
     * 保存事件消息
     */
    public function saveEventMessage()
    {
        if ($this->weixin->msg->Event == 'LOCATION') {
            #保存地理位置
            $this->saveLocationMessage();
        } else { #} if ($this->weixin->msg->Event == 'click') {
            #保存点击事件
            $this->weixin->log('事件类型：' . $this->weixin->msg->Event . '，放弃保存');
        }
    }

    /**
     * 检查是否超时
     */
    public function checkTimeout()
    {
        $this->weixin->execute_time = number_format(microtime(true) - $this->weixin->startTime, 1);

        if ($this->weixin->execute_time >= 3) {
            $this->weixin->error('timeout');
        }
    }

    /**
     * 回调方法
     */
    public function callback()
    {
        $this->weixin->log('进入回调流程');
        #有高级接口，并且存在回调
        if ($this->weixin && $this->weixin->account->advanced_interface == 'Y' && $this->weixin->callback) {
            $this->weixin->log('执行回调流程');
            $result = call_user_func_array($this->weixin->callback, $this->weixin->callbackParams);
            $this->weixin->log('回调结果');
            $this->weixin->log($result);
        }
    }

    /**
     * 同步用户信息
     */
    public function autoSyncUserInfo()
    {
        if ($this->weixin->account->advanced_interface == 'Y' && (time() - $this->weixin->user->last_update_time > 86400 * 3)) {

            $this->weixin->log('需要同步用户信息：' . $this->weixin->user->nickname);
            $result = $this->weixin->getSubscriberInfo($this->weixin->user->open_id);

            if ($result['errcode'] === 0) {

                $this->weixin->user->setAttributes(array(
                        'nickname' => $this->weixin->user->nickname == '无名大侠' ? $result['data']['sex'] : $this->weixin->user->nickname,
                        'sex' => $result['data']['sex'],
                        'language' => $result['data']['language'],
                        'city' => $result['data']['city'],
                        'province' => $result['data']['province'],
                        'country' => $result['data']['country'],
                        'headimgurl' => $result['data']['headimgurl'],
                        'dateline' => $result['data']['subscribe_time'],
                        'status' => $result['data']['subscribe'] == 0 ? 'N' : 'Y',
                        'last_update_time' => time(),
                ));

                $this->weixin->log('获取官方信息成功');
                $this->weixin->log($this->weixin->user->getAttributes());

            } else {
                $this->weixin->user->setAttribute('last_update_time', time());
            }

        }
    }
}