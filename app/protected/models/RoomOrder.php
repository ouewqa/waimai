<?php

/**
 * This is the model class for table "room_order".
 *
 * The followings are the available columns in table 'room_order':
 * @property string $weixin_id
 * @property string $room_id
 * @property string $order_time
 * @property integer $number_of_people
 * @property string $dateline
 * @property string $status
 */
class RoomOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'room_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, room_id', 'required'),
			array('number_of_people', 'numerical', 'integerOnly'=>true),
			array('weixin_id, room_id, order_time, dateline', 'length', 'max'=>11),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('weixin_id, room_id, order_time, number_of_people, dateline, status', 'safe', 'on'=>'search'),
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
			'room_id' => 'Room',
			'order_time' => '预订时间',
			'number_of_people' => '人数',
			'dateline' => 'Dateline',
			'status' => 'Status',
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
		$criteria->compare('room_id',$this->room_id,true);
		$criteria->compare('order_time',$this->order_time,true);
		$criteria->compare('number_of_people',$this->number_of_people);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
