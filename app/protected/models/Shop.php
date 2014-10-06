<?php

/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $name
 * @property integer $sn
 * @property string $description
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $telephone
 * @property string $mobile
 * @property string $map_point
 * @property string $opening_time_start
 * @property string $opening_time_end
 * @property string $minimum_charge
 * @property string $express_fee
 * @property string $delivery_explain
 * @property string $theme
 * @property string $style
 * @property string $default
 * @property string $push_method
 * @property string $push_device
 * @property string $push_device_no
 * @property string $push_device_key
 * @property integer $number_of_copies
 * @property integer $last_device_check_time
 * @property string $status
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property Announcement[] $announcements
 * @property Feedback[] $feedbacks
 * @property PrintLog[] $printLogs
 * @property Room[] $rooms
 * @property WeixinAccount $weixinAccount
 * @property ShopOrder[] $shopOrders
 */
class Shop extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, name, province, city, district, address, telephone', 'required'),
                array('sn, number_of_copies, last_device_check_time', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, mobile, dateline', 'length', 'max' => 11),
                array('name, province, city, district, theme, style, push_method, push_device, push_device_no, push_device_key', 'length', 'max' => 45),
                array('address, delivery_explain', 'length', 'max' => 255),
                array('telephone', 'length', 'max' => 15),
                array('map_point', 'length', 'max' => 128),
                array('opening_time_start, opening_time_end', 'length', 'max' => 5),
                array('minimum_charge, express_fee', 'length', 'max' => 8),
                array('default, status', 'length', 'max' => 1),
                array('description', 'safe'),


            #手动修改
                array('mobile', 'length', 'min' => 11, 'max' => 11, 'message' => '手机号码不正确'),


            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, name, sn, description, province, city, district, address, telephone, mobile, map_point, opening_time_start, opening_time_end, minimum_charge, express_fee, delivery_explain, theme, style, default, push_method, push_device, push_device_no, push_device_key, number_of_copies, last_device_check_time, status, dateline', 'safe', 'on' => 'search'),
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
                'announcements' => array(self::HAS_MANY, 'Announcement', 'shop_id'),
                'feedbacks' => array(self::HAS_MANY, 'Feedback', 'shop_id'),
                'printLogs' => array(self::HAS_MANY, 'PrintLog', 'shop_id'),
                'rooms' => array(self::HAS_MANY, 'Room', 'shop_id'),
                'weixinAccount' => array(self::BELONGS_TO, 'WeixinAccount', 'weixin_account_id'),
                'shopOrders' => array(self::HAS_MANY, 'ShopOrder', 'shop_id'),
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
                'name' => '店铺名称',
                'sn' => '门店编号',
                'description' => '介绍',
                'province' => '省份',
                'city' => '市',
                'district' => '区',
                'address' => '详细地址',
                'telephone' => '座机',
                'mobile' => '手机号码',
                'map_point' => '地理位置',
                'opening_time_start' => '营业开始时间',
                'opening_time_end' => '营业结束时间',
                'minimum_charge' => '起送价',
                'express_fee' => '送餐费',
                'delivery_explain' => '外送说明',
                'theme' => '风格',
                'style' => '样式',
                'default' => '是否为默认店铺',
                'push_method' => '推送方式',
                'push_device' => '推送设备',
                'push_device_no' => '推送设备编号',
                'push_device_key' => '推送设备KEY',
                'number_of_copies' => '打印份数',
                'last_device_check_time' => '最后一次设备检测时间',
                'status' => 'Status',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sn', $this->sn);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('province', $this->province, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('district', $this->district, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('telephone', $this->telephone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('map_point', $this->map_point, true);
        $criteria->compare('opening_time_start', $this->opening_time_start, true);
        $criteria->compare('opening_time_end', $this->opening_time_end, true);
        $criteria->compare('minimum_charge', $this->minimum_charge, true);
        $criteria->compare('express_fee', $this->express_fee, true);
        $criteria->compare('delivery_explain', $this->delivery_explain, true);
        $criteria->compare('theme', $this->theme, true);
        $criteria->compare('style', $this->style, true);
        $criteria->compare('default', $this->default, true);
        $criteria->compare('push_method', $this->push_method, true);
        $criteria->compare('push_device', $this->push_device, true);
        $criteria->compare('push_device_no', $this->push_device_no, true);
        $criteria->compare('push_device_key', $this->push_device_key, true);
        $criteria->compare('number_of_copies', $this->number_of_copies);
        $criteria->compare('last_device_check_time', $this->last_device_check_time);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('dateline', $this->dateline, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Shop the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeValidate()
    {
        if (!$this->minimum_charge) {
            $this->minimum_charge = 0;
        }
        if (!$this->express_fee) {
            $this->express_fee = 0;
        }
        return parent::beforeValidate();
    }


    protected function beforeSave()
    {
        #判断是否是第一个
        $count = Shop::model()->count('weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => Yii::app()->user->weixin_account_id,
        ));

        if (!$count) {
            $this->default = 'Y';
        }
        return parent::beforeSave();
    }


    /**
     * 查看默认的门店
     * @return bool|CActiveRecord
     */
    public function findDefaultShop()
    {
        if (isset(Yii::app()->user->weixin_account_id)) {

            /*echo '取默认门店时Yii::app()->user->weixin_account_id=', Yii::app()->user->weixin_account_id, PHP_EOL;*/

            $count = $this->count('weixin_account_id=:weixin_account_id', array(
                    ':weixin_account_id' => Yii::app()->user->weixin_account_id,
            ));

            if (!$count) {
                return false;
            } else {

                $model = $this->find('`default`=:default AND weixin_account_id=:weixin_account_id', array(
                        ':default' => 'Y',
                        ':weixin_account_id' => Yii::app()->user->weixin_account_id,
                ));

                if (!$model) {
                    $model = $this->cache(86400)->find('weixin_account_id=:weixin_account_id', array(
                            ':weixin_account_id' => Yii::app()->user->weixin_account_id,
                    ));
                    $model->default = 'Y';
                    $model->save();
                }

                return $model;
            }
        }
    }

    /**
     * 查找自己的的门店
     * @param $weixin_account_id
     * @return CActiveRecord[]
     */
    public function findOwnerShops($weixin_account_id)
    {
        return $this->findAll('weixin_account_id=:weixin_account_id AND status=:status', array(
                ':weixin_account_id' => $weixin_account_id,
                ':status' => 'Y',
        ));
    }
}
