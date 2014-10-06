<?php

/**
 * This is the model class for table "weixin_account".
 *
 * The followings are the available columns in table 'weixin_account':
 * @property string $id
 * @property string $admin_id
 * @property string $name
 * @property string $source
 * @property string $type
 * @property string $appid
 * @property string $appsecret
 * @property string $token
 * @property string $access_token
 * @property string $access_token_expire_time
 * @property string $baidu_ak
 * @property string $advanced_interface
 * @property string $default
 * @property string $need_mobile_verify
 * @property string $notify_customer_method
 * @property string $count_request
 * @property string $debug
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Material[] $materials
 * @property PaymentConfig[] $paymentConfigs
 * @property Shop[] $shops
 * @property ShopDish[] $shopDishes
 * @property ShopDishCategory[] $shopDishCategories
 * @property Weixin[] $weixins
 * @property Admin $admin
 * @property WeixinGroup[] $weixinGroups
 * @property WeixinHelp[] $weixinHelps
 * @property WeixinLocation[] $weixinLocations
 * @property WeixinLog[] $weixinLogs
 * @property WeixinMenu[] $weixinMenus
 * @property WeixinMessage[] $weixinMessages
 * @property WeixinQrcode[] $weixinQrcodes
 */
class WeixinAccount extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin_account';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('admin_id, name, source', 'required'),
                array('admin_id, access_token_expire_time, count_request', 'length', 'max' => 11),
                array('name, source, token, baidu_ak', 'length', 'max' => 45),
                array('type, advanced_interface, default, need_mobile_verify, debug, status', 'length', 'max' => 1),
                array('appid', 'length', 'max' => 18),
                array('appsecret', 'length', 'max' => 32),
                array('access_token', 'length', 'max' => 512),
                array('notify_customer_method', 'length', 'max' => 6),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, admin_id, name, source, type, appid, appsecret, token, access_token, access_token_expire_time, baidu_ak, advanced_interface, default, need_mobile_verify, notify_customer_method, count_request, debug, status', 'safe', 'on' => 'search'),
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
                'materials' => array(self::HAS_MANY, 'Material', 'weixin_account_id'),
                'paymentConfigs' => array(self::HAS_MANY, 'PaymentConfig', 'weixin_account_id'),
                'shops' => array(self::HAS_MANY, 'Shop', 'weixin_account_id'),
                'shopDishes' => array(self::HAS_MANY, 'ShopDish', 'weixin_account_id'),
                'shopDishCategories' => array(self::HAS_MANY, 'ShopDishCategory', 'weixin_account_id'),
                'weixins' => array(self::HAS_MANY, 'Weixin', 'weixin_account_id'),
                'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
                'weixinGroups' => array(self::HAS_MANY, 'WeixinGroup', 'weixin_account_id'),
                'weixinHelps' => array(self::HAS_MANY, 'WeixinHelp', 'weixin_account_id'),
                'weixinLocations' => array(self::HAS_MANY, 'WeixinLocation', 'weixin_account_id'),
                'weixinLogs' => array(self::HAS_MANY, 'WeixinLog', 'weixin_account_id'),
                'weixinMenus' => array(self::HAS_MANY, 'WeixinMenu', 'weixin_account_id'),
                'weixinMessages' => array(self::HAS_MANY, 'WeixinMessage', 'weixin_account_id'),
                'weixinQrcodes' => array(self::HAS_MANY, 'WeixinQrcode', 'weixin_account_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'admin_id' => 'Admin',
                'name' => '公众号名称',
                'source' => '原始号',
                'type' => '类型D 订阅号F 服务号',
                'appid' => 'appID',
                'appsecret' => 'appSecret',
                'token' => '识别Token，用户自定义',
                'access_token' => '访问token',
                'access_token_expire_time' => '访问token过期时间',
                'baidu_ak' => '百度AK',
                'advanced_interface' => '是否拥有高级接口',
                'default' => '默认账号',
                'need_mobile_verify' => '是否需要手机号码验证后才能提交订单',
                'notify_customer_method' => '通知用户方式',
                'count_request' => '请求数',
                'debug' => '是否开启调试',
                'status' => '状态',
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
        $criteria->compare('admin_id', $this->admin_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('source', $this->source, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('appid', $this->appid, true);
        $criteria->compare('appsecret', $this->appsecret, true);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('access_token', $this->access_token, true);
        $criteria->compare('access_token_expire_time', $this->access_token_expire_time, true);
        $criteria->compare('baidu_ak', $this->baidu_ak, true);
        $criteria->compare('advanced_interface', $this->advanced_interface, true);
        $criteria->compare('default', $this->default, true);
        $criteria->compare('need_mobile_verify', $this->need_mobile_verify, true);
        $criteria->compare('notify_customer_method', $this->notify_customer_method, true);
        $criteria->compare('count_request', $this->count_request, true);
        $criteria->compare('debug', $this->debug, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WeixinAccount the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    protected function beforeValidate()
    {
        /*if (Yii::app()->request->isPostRequest) {
            if ($this->advanced_interface == 'Y' && (!$this->appid || !$this->appsecret)) {
                $this->addError('advanced_interface', '高级接口，必须填写appID及appSecret');
            }
        }*/


        if (!$this->notify_customer_method) {
            $this->notify_customer_method = null;
        }

        if (!$this->need_mobile_verify) {
            $this->need_mobile_verify = null;
        }

        return parent::beforeValidate();
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->token = substr(md5(microtime(true)), 0, 6);
        } else {
            $this->token = $this->token ? $this->token : substr(md5(microtime(true)), 0, 6);
        }


        if (Yii::app()->request->isPostRequest) {
            #判断是否是第一个
            $count = WeixinAccount::model()->count('admin_id=:admin_id', array(
                    ':admin_id' => Yii::app()->user->id,
            ));

            if (!$count) {
                $this->default = 'Y';
            }
        }

        return parent::beforeSave();
    }


    /**
     * 查找默认账号
     * @return array|CActiveRecord|mixed|null
     */
    public function findDefaultAccount()
    {

        $count = $this->count('admin_id=:admin_id', array(
                ':admin_id' => Yii::app()->user->id,
        ));

        if (!$count) {
            return false;
        } else {
            $model = $this->find('`default`=:default AND admin_id=:admin_id', array(
                    ':default' => 'Y',
                    ':admin_id' => Yii::app()->user->id,
            ));

            if (!$model) {
                $model = $this->cache(86400)->find('admin_id=:admin_id', array(
                        ':admin_id' => Yii::app()->user->id,
                ));
                $model->default = 'Y';
                $model->save();
            }

            return $model;
        }

    }
}
