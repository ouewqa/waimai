<?php

/**
 * This is the model class for table "used_addresses".
 *
 * The followings are the available columns in table 'used_addresses':
 * @property string $id
 * @property string $weixin_id
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $realname
 * @property string $mobile
 * @property integer $dateline
 * @property string $status
 *
 * The followings are the available model relations:
 * @property ShopOrder[] $shopOrders
 * @property Weixin $weixin
 */
class UsedAddresses extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'used_addresses';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_id, province, city, district, address, realname, mobile', 'required'),
                array('dateline', 'numerical', 'integerOnly' => true),
                array('weixin_id, mobile', 'length', 'max' => 11),
                array('province, city, district, address, realname', 'length', 'max' => 45),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_id, province, city, district, address, realname, mobile, dateline, status', 'safe', 'on' => 'search'),
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
                'shopOrders' => array(self::HAS_MANY, 'ShopOrder', 'used_addresses_id'),
                'weixin' => array(self::BELONGS_TO, 'Weixin', 'weixin_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'weixin_id' => 'Weixin',
                'province' => '省',
                'city' => '市',
                'district' => '区',
                'address' => '详情地址',
                'realname' => '姓名',
                'mobile' => '手机号码',
                'dateline' => 'Dateline',
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
        $criteria->compare('weixin_id', $this->weixin_id, true);
        $criteria->compare('province', $this->province, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('district', $this->district, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('realname', $this->realname, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('dateline', $this->dateline);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UsedAddresses the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findOwnerAddress($weixin_id, $limit = 5)
    {
        return $this->findAll(array(
                'condition' => 'weixin_id=:weixin_id AND status=:status',
                'params' => array(
                        ':weixin_id' => $weixin_id,
                        ':status' => 'Y',
                ),
                'order' => 'dateline DESC',
                'limit' => $limit,
        ));
    }

    /*protected function beforeValidate()
    {
        $weixin = Weixin::model()->with('weixinAccount')->findByPk($this->weixin_id);
        if ($weixin->weixinAccount->need_mobile_verify == 'Y') {

        }

        return parent::beforeValidate();
    }*/
}
