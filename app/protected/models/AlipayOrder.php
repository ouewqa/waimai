<?php

/**
 * This is the model class for table "alipay_order".
 *
 * The followings are the available columns in table 'alipay_order':
 * @property string $id
 * @property string $weixin_id
 * @property string $seller_email
 * @property string $trade_no
 * @property string $price
 * @property string $quantity
 * @property string $money
 * @property string $subject
 * @property string $description
 * @property string $product
 * @property string $url
 * @property string $status
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 */
class AlipayOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'alipay_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, trade_no, money', 'required'),
			array('weixin_id, quantity, money, dateline', 'length', 'max'=>11),
			array('seller_email, url', 'length', 'max'=>255),
			array('trade_no, subject, product', 'length', 'max'=>45),
			array('price', 'length', 'max'=>10),
			array('status', 'length', 'max'=>1),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_id, seller_email, trade_no, price, quantity, money, subject, description, product, url, status, dateline', 'safe', 'on'=>'search'),
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
			'seller_email' => '买家',
			'trade_no' => '订单号',
			'price' => '单价',
			'quantity' => 'Quantity',
			'money' => 'Money',
			'subject' => '交易标题',
			'description' => '交易描述',
			'product' => '产品号',
			'url' => '产品网址',
			'status' => 'Y 支付成功　N 支付失败　W 等待支付',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('seller_email',$this->seller_email,true);
		$criteria->compare('trade_no',$this->trade_no,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlipayOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
