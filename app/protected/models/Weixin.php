<?php

/**
 * This is the model class for table "weixin".
 *
 * The followings are the available columns in table 'weixin':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $open_id
 * @property integer $weixin_group_id
 * @property string $mobile
 * @property string $mobile_is_verify
 * @property string $weixin_account
 * @property string $realname
 * @property string $nickname
 * @property integer $sex
 * @property string $credit
 * @property integer $birthday
 * @property string $qq
 * @property string $language
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $headimgurl
 * @property string $qrcode_ticket
 * @property string $qrcode_create_time
 * @property string $last_update_time
 * @property string $last_request_time
 * @property string $last_response_time
 * @property integer $last_location_time
 * @property integer $last_push_time
 * @property string $status
 * @property integer $updatetime
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property AlipayOrder[] $alipayOrders
 * @property Card[] $cards
 * @property ShopDish[] $shopDishes
 * @property Feedback[] $feedbacks
 * @property Room[] $rooms
 * @property ShareLog[] $shareLogs
 * @property ShopOrder[] $shopOrders
 * @property UsedAddresses[] $usedAddresses
 * @property WeixinAccount $weixinAccount
 * @property WeixinMedia[] $weixinMedias
 * @property WeixinMessage[] $weixinMessages
 */
class Weixin extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, open_id', 'required'),
                array('weixin_group_id, sex, birthday, last_location_time, last_push_time, updatetime', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, mobile, credit, qrcode_create_time, last_update_time, last_request_time, last_response_time, dateline', 'length', 'max' => 11),
                array('open_id, weixin_account, realname, nickname, qq, language, country, province, city, district', 'length', 'max' => 45),
                array('mobile_is_verify, status', 'length', 'max' => 1),
                array('address, headimgurl, qrcode_ticket', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, open_id, weixin_group_id, mobile, mobile_is_verify, weixin_account, realname, nickname, sex, credit, birthday, qq, language, country, province, city, district, address, headimgurl, qrcode_ticket, qrcode_create_time, last_update_time, last_request_time, last_response_time, last_location_time, last_push_time, status, updatetime, dateline', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
                'alipayOrders' => array(self::HAS_MANY, 'AlipayOrder', 'weixin_id'),
                'cards' => array(self::HAS_MANY, 'Card', 'weixin_id'),
                'shopDishes' => array(self::MANY_MANY, 'ShopDish', 'shopping_cart(weixin_id, shop_dish_id)'),
                'feedbacks' => array(self::HAS_MANY, 'Feedback', 'weixin_id'),
                'rooms' => array(self::MANY_MANY, 'Room', 'room_order(weixin_id, room_id)'),
                'shareLogs' => array(self::HAS_MANY, 'ShareLog', 'weixin_id'),
                'shopOrders' => array(self::HAS_MANY, 'ShopOrder', 'weixin_id'),
                'usedAddresses' => array(self::HAS_MANY, 'UsedAddresses', 'weixin_id'),
                'weixinAccount' => array(self::BELONGS_TO, 'WeixinAccount', 'weixin_account_id'),
                'weixinMedias' => array(self::HAS_MANY, 'WeixinMedia', 'weixin_id'),
                'weixinMessages' => array(self::HAS_MANY, 'WeixinMessage', 'weixin_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'weixin_account_id' => 'Weixin Account',
                'open_id' => 'Open',
                'weixin_group_id' => '微信群组',
                'mobile' => '手机号码',
                'mobile_is_verify' => 'Mobile Is Verify',
                'weixin_account' => '个人微信账号',
                'realname' => '真实名字',
                'nickname' => '用户昵称',
                'sex' => '性别',
                'credit' => '积分',
                'birthday' => '出生年月',
                'qq' => 'QQ',
                'language' => 'Language',
                'country' => 'Country',
                'province' => '省',
                'city' => '市',
                'district' => '区',
                'address' => '具体地址',
                'headimgurl' => '头像',
                'qrcode_ticket' => '定制二维码地址',
                'qrcode_create_time' => '二维码生成时间',
                'last_update_time' => '最后同时微信API时间',
                'last_request_time' => '最后请求时间',
                'last_response_time' => '最后系统响应时间，由用户触发',
                'last_location_time' => '最后一次地理位置时间',
                'last_push_time' => '最后推送时间，由系统触发',
                'status' => '状态',
                'updatetime' => '用户最后活动时间',
                'dateline' => 'Dateline',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);
        $criteria->compare('open_id', $this->open_id, true);
        $criteria->compare('weixin_group_id', $this->weixin_group_id);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('mobile_is_verify', $this->mobile_is_verify, true);
        $criteria->compare('weixin_account', $this->weixin_account, true);
        $criteria->compare('realname', $this->realname, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('credit', $this->credit, true);
        $criteria->compare('birthday', $this->birthday);
        $criteria->compare('qq', $this->qq, true);
        $criteria->compare('language', $this->language, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('province', $this->province, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('district', $this->district, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('headimgurl', $this->headimgurl, true);
        $criteria->compare('qrcode_ticket', $this->qrcode_ticket, true);
        $criteria->compare('qrcode_create_time', $this->qrcode_create_time, true);
        $criteria->compare('last_update_time', $this->last_update_time, true);
        $criteria->compare('last_request_time', $this->last_request_time, true);
        $criteria->compare('last_response_time', $this->last_response_time, true);
        $criteria->compare('last_location_time', $this->last_location_time);
        $criteria->compare('last_push_time', $this->last_push_time);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('updatetime', $this->updatetime);
        $criteria->compare('dateline', $this->dateline, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Weixin the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeValidate()
    {

        if ($this->birthday) {
            $this->birthday = !preg_match('/\d{9,11}/', $this->birthday) ? strtotime($this->birthday) : $this->birthday;
        } else {
            $this->birthday = null;
        }

        if (!$this->qq) {
            $this->qq = null;
        }

        if (!$this->mobile) {
            $this->mobile = null;
        }


        if (!$this->realname) {
            $this->realname = null;
        }

        if (!$this->weixin_account_id) {
            $this->weixin_account_id = null;
        }


        if (!$this->nickname) {
            $this->nickname = '无名大侠';
        }

        return parent::beforeValidate();
    }


    protected function beforeSave()
    {
        if ($this->isNewRecord && !$this->dateline) {
            $this->dateline = DATELINE;
        }

        $this->updatetime = DATELINE;
        return parent::beforeSave();
    }


    /**
     * 邮件验证码发送
     */
    /*protected function afterSave()
    {
        if (Yii::app()->request->isPostRequest && $this->email && $this->email_is_verify == 'W') {
            $this->sendEmailVerify();
        }
        return parent::afterSave();
    }*/

    /**
     * 查找单个订阅者
     * @param $open_id
     * @return CActiveRecord
     */
    public function findByOpenId($open_id)
    {
        return $this->find('open_id=:open_id AND status=:status', array(
                'open_id' => $open_id,
                'status' => 'Y',
        ));
    }

    /**
     * 查找所有订阅者
     * @param $weixin_account_id
     * @return CActiveRecord
     */
    public function findAllSubscriber($weixin_account_id)
    {
        return $this->findAll(array(
                        'condition' => 'weixin_account_id=:weixin_account_id AND status=:status',
                        'params' => array(
                                ':weixin_account_id' => $weixin_account_id,
                                ':status' => 'Y',
                        ),
                )
        );
    }

    /**
     * 查找用户
     * @param $open_id
     * @return CActiveRecord
     */
    public function getUserByOpenId($open_id)
    {
        return $this->find(
                'open_id=:open_id', array(
                        ':open_id' => $open_id,
                )
        );
    }


    /**
     * 发送红包
     * @return int
     */
    public function sendRedPacket()
    {
        return $this->updateAll(
                array(
                        'coin' => 50,
                ),
                'coin <:coin AND status="Y"',
                array(
                        ':coin' => 50
                )
        );
    }

    /**
     * 发送激活邮件
     * @return bool
     */
    public function sendEmailVerify()
    {
        return EmailHelper::send(array(
                'email' => $this->email,
                'subject' => $this->nickname . '，来自日语家园的邮件地址验证。',
                'data' => array(
                        'title' => '日语家园邮件地址验证',
                        'content' => '
                        <p>点击下面链接验证邮箱地址，一周内有效。</p>
                        <p>' .
                                CHtml::link(
                                        'http://wx.ribenyu.cn/weixin/default/emailVerify/weixin_id/' . $this->id . '/code/' . VerificationCode::model()->getCode('email', $this->id, $this->email, 7),
                                        'http://wx.ribenyu.cn/weixin/default/emailVerify/weixin_id/' . $this->id . '/code/' . VerificationCode::model()->getCode('email', $this->id, $this->email, 7)
                                )

                                . '</p>
                        ',
                ),
                'view' => 'emailVerify',
                'debug' => false,
        ));
    }


    /**
     * 当前邮箱地址验证成功后，发邮件通知，附带广告
     * @return bool
     */
    public function sendEmailVerifySuccess()
    {
        #保存二维码

        if ($this->qrcode_ticket) {
            $qrcode = CHtml::image('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($this->qrcode_ticket), $this->nickname . '的微信二维码');

            $content = '
                        <h3>日语家园微信号中如何快速获取金币？</h3>
                        <p><font size="4">除了每天签到领金币外，最快的方式就是通过二维码邀请好友，每邀请一位好友将有100枚金币，最高可获取1000枚金币。</font></p>
                        <h3>你的专属二维码：</h3>
                        <p>
                        ' . $qrcode . '
                         </p>
                        <p><font size="4">你可以将此图片或转发到你朋友的QQ或者邮箱，或发到您的QQ群、日语相关的论坛与博客，只要有人扫描了这张二维码图片，系统会自动跟踪本次关注，关注成功后，系统会奖励你相应的金币。</font></p>
                        <blockquote>
                        <h3>说些题外话：</h3>
                        <p>为什么要设立金币这项限制？</p>
                        <p>原因很简单，家园不是一个盈利组织，但家园需要推广，需要大家共同出力去宣传日语家园。<br />如果你觉得家园功能做得太烂，不值得推荐，那请忽略我这些请求。<br />如果你觉得家园做的还可以，那么，请花点时间为家园推广，帮助家园壮大。也许只是三五分钟，却能为家园注入一股强劲的动力！</p>
                        <h4>那么，你愿意帮助家园么……</h4>
                        <p>如果你有更多想法，可以直接与我联系！邮箱地址见右下角。</p>
                        </blockquote>
    ';
        } else {

            $qrcode = 'http://www.ribenyu.cn/data/attachment/album/201312/12/123112pwt76nta7t2r792l.jpg';
            $content = '
                        <blockquote>
                        <h3>说些题外话：</h3>
                        <p>为什么要设立金币这项限制？</p>
                        <p>原因很简单，家园不是一个盈利组织，但家园需要推广，需要大家共同出力去宣传日语家园。<br />如果你觉得家园功能做得太烂，不值得推荐，那请忽略我这些请求。<br />如果你觉得家园做的还可以，那么，请花点时间为家园推广，帮助家园壮大。也许只是三五分钟，却能为家园注入一股强劲的动力！</p>
                        <h4>那么，你愿意帮助家园么……</h4>
                        <p>如果你有更多想法，可以直接与我联系！邮箱地址见右下角。</p>
                        </blockquote>
                        <h3>日语家园二维码：</h3>
                        <p>
                        ' . $qrcode . '
                         </p>
                        <p><font size="4">你可以将此图片或转发到你朋友的QQ或者邮箱，或发到您的QQ群、日语相关的论坛与博客。</font></p>
                        ';
        }

        return EmailHelper::send(array(
                'email' => $this->email,
                'subject' => $this->nickname . '，你的邮箱地址已验证成功。',
                'data' => array(
                        'title' => '您在日语家园的邮件地址验证成功！',
                        'content' => $content,
                ),
                'view' => 'email',
                'debug' => false,
        ));
    }

    /**
     * 给当前用户发送邮件
     * @param $title
     * @param $content
     * @param string $view
     * @param bool $debug
     * @return bool
     */
    public function sendEmail($title, $content, $view = 'email', $debug = false)
    {
        if ($this->email) {
            return EmailHelper::send(array(
                    'email' => $this->email,
                    'subject' => $title,
                    'data' => array(
                            'title' => $title,
                            'content' => $content,
                    ),
                    'view' => $view,
                    'debug' => $debug,
            ));
        }
    }

    public function getGroupName()
    {
        $groups = WeixinGroup::model()->cache(86400)->find('group_id=:group_id AND weixin_account_id=:weixin_account_id', array(
                ':group_id' => $this->weixin_group_id,
                ':weixin_account_id' => $this->weixin_account_id,
        ));

        if ($groups) {
            return $groups->name;
        }
    }
}
