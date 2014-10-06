<?php

/**
 * This is the model class for table "shopping_cart".
 *
 * The followings are the available columns in table 'shopping_cart':
 * @property string $weixin_id
 * @property string $shop_dish_id
 * @property integer $number
 * @property string $dateline
 */
class ShoppingCart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shopping_cart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, shop_dish_id', 'required'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('weixin_id, shop_dish_id, dateline', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('weixin_id, shop_dish_id, number, dateline', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'weixin_id' => 'Weixin',
			'shop_dish_id' => 'Shop Dish',
			'number' => '数量',
			'dateline' => '添加时间',
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

		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('shop_dish_id',$this->shop_dish_id,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShoppingCart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
