<?php

/**
 * This is the model class for table "shop_order_item".
 *
 * The followings are the available columns in table 'shop_order_item':
 * @property string $shop_order_id
 * @property string $shop_dish_id
 * @property integer $number
 * @property string $price
 */
class ShopOrderItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shop_order_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_order_id, shop_dish_id', 'required'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('shop_order_id, shop_dish_id', 'length', 'max'=>11),
			array('price', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('shop_order_id, shop_dish_id, number, price', 'safe', 'on'=>'search'),
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

            #手动添加
                'shopDish' => array(self::BELONGS_TO, 'ShopDish', 'shop_dish_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shop_order_id' => 'Shop Order',
			'shop_dish_id' => 'Shop Dish',
			'number' => '数量',
			'price' => '价格',
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

		$criteria->compare('shop_order_id',$this->shop_order_id,true);
		$criteria->compare('shop_dish_id',$this->shop_dish_id,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopOrderItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
