56<?php

/**
 * This is the model class for table "weixin_notification".
 *
 * The followings are the available columns in table 'weixin_notification':
 * @property string $id
 * @property string $to_weixin_id
 * @property string $from_weixin_id
 * @property string $message
 * @property string $type
 * @property string $read
 * @property string $dateline
 */
class WeixinNotification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('to_weixin_id, from_weixin_id, message', 'required'),
			array('to_weixin_id, from_weixin_id, dateline', 'length', 'max'=>11),
			array('message', 'length', 'max'=>1024),
			array('type', 'length', 'max'=>45),
			array('read', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, to_weixin_id, from_weixin_id, message, type, read, dateline', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'to_weixin_id' => 'To Weixin',
			'from_weixin_id' => 'From Weixin',
			'message' => 'Message',
			'type' => '消息指向类型，如问答、站内通知、公告等',
			'read' => '是否已读',
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
		$criteria->compare('to_weixin_id',$this->to_weixin_id,true);
		$criteria->compare('from_weixin_id',$this->from_weixin_id,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('read',$this->read,true);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinNotification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
