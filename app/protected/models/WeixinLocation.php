<?php

/**
 * This is the model class for table "weixin_location".
 *
 * The followings are the available columns in table 'weixin_location':
 * @property string $id
 * @property string $weixin_id
 * @property string $weixin_account_id
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $precision
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $street
 * @property string $street_number
 * @property string $query_address
 * @property integer $dateline
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinLocation extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin_location';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_id, weixin_account_id', 'required'),
                array('dateline', 'numerical', 'integerOnly' => true),
                array('weixin_id, weixin_account_id', 'length', 'max' => 11),
                array('address', 'length', 'max' => 255),
                array('latitude, longitude, precision', 'length', 'max' => 15),
                array('province, city, district, street, street_number, query_address', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_id, weixin_account_id, address, latitude, longitude, precision, province, city, district, street, street_number, query_address, dateline', 'safe', 'on' => 'search'),
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
                'weixinAccount' => array(self::BELONGS_TO, 'WeixinAccount', 'weixin_account_id'),
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
                'weixin_account_id' => 'Weixin Account',
                'address' => '具体地址',
                'latitude' => '纬度',
                'longitude' => '经度',
                'precision' => '精度',
                'province' => '省份',
                'city' => '城市',
                'district' => '区',
                'street' => 'Street',
                'street_number' => 'Street Number',
                'query_address' => 'Query Address',
                'dateline' => '时间',
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
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('precision', $this->precision, true);
        $criteria->compare('province', $this->province, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('district', $this->district, true);
        $criteria->compare('street', $this->street, true);
        $criteria->compare('street_number', $this->street_number, true);
        $criteria->compare('query_address', $this->query_address, true);
        $criteria->compare('dateline', $this->dateline);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WeixinLocation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 自动保存地址更新时间
     * @return bool
     */
    protected function beforeSave()
    {
        $this->dateline = DATELINE;
        return parent::beforeSave();
    }


    /**
     * 获取我的上一次地址位置
     * @param $weixin_id
     * @return CActiveRecord
     */
    public function findMyLastLocation($weixin_id)
    {
        return $this->find('weixin_id=:weixin_id', array(
                ':weixin_id' => $weixin_id,
        ));

    }

    /**
     * 获取所有最新的地位位置
     * @param $weixin_account_id
     * @param int $interval
     * @param int $limit
     * @return CActiveRecord
     */
    public function findLatestLocation($weixin_account_id, $limit = 300, $interval = 0)
    {
        $model = $this->findAll(array(
                'select' => 'weixin_id, address, latitude, longitude, dateline',
                'condition' => 't.weixin_account_id=:weixin_account_id AND t.dateline >= :dateline',
                'params' => array(
                        ':weixin_account_id' => $weixin_account_id,
                        ':dateline' => $interval ? time() - $interval : 0,
                ),
                'with' => 'weixin',
                'distinct' => 't.open_id',
                'order' => 't.dateline DESC',
                'limit' => $limit,
        ));
        $data = array();
        foreach ($model as $key => $value) {
            $data[] = array(
                    'nickname' => $value->weixin->nickname,
                    'sex' => OutputHelper::getSexList($value->weixin->sex),
                    'headimgurl' => $value->weixin->headimgurl,
                    'weixin_account' => $value->weixin->weixin_account,
                    'qq' => $value->weixin->qq,
                    'jp_level' => $value->weixin->jp_level,
                    'identity' => $value->weixin->identity,
                    'city' => $value->weixin->city,


                    'weixin_id' => $value->weixin_id,
                    'address' => $value->address,
                    'longitude' => $value->longitude,
                    'latitude' => $value->latitude,
                    'dateline' => OutputHelper::timeFormat($value->dateline),
            );
        }

        return $data;
    }
}
