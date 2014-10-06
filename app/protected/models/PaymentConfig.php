<?php

/**
 * This is the model class for table "payment_config".
 *
 * The followings are the available columns in table 'payment_config':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $payment_method_id
 * @property string $key
 * @property string $value
 *
 * The followings are the available model relations:
 * @property PaymentMethod $paymentMethod
 * @property WeixinAccount $weixinAccount
 */
class PaymentConfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payment_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_account_id, payment_method_id, key, value', 'required'),
			array('weixin_account_id, payment_method_id', 'length', 'max'=>11),
			array('key', 'length', 'max'=>45),
			array('value', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_account_id, payment_method_id, key, value', 'safe', 'on'=>'search'),
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
			'paymentMethod' => array(self::BELONGS_TO, 'PaymentMethod', 'payment_method_id'),
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
			'weixin_account_id' => 'Weixin Account',
			'payment_method_id' => 'Payment Method',
			'key' => 'Key',
			'value' => 'Value',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('weixin_account_id',$this->weixin_account_id,true);
		$criteria->compare('payment_method_id',$this->payment_method_id,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PaymentConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function findOwnerPayments($weixin_account_id)
    {
        return $this->findAll(array(
                'condition' => 'weixin_account_id=:weixin_account_id AND `key`=:key AND `value`=:value',
                'params' => array(
                        ':weixin_account_id' => $weixin_account_id,
                        ':key' => 'status',
                        ':value' => 'Y',
                ),
                'group' => 'payment_method_id',
                'with' => 'paymentMethod',
        ));
    }
}
