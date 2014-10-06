<?php

/**
 * This is the model class for table "weixin_log".
 *
 * The followings are the available columns in table 'weixin_log':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $weixin_id
 * @property string $type
 * @property string $content
 * @property double $execute_time
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_account_id, weixin_id', 'required'),
			array('execute_time', 'numerical'),
			array('weixin_account_id, weixin_id, dateline', 'length', 'max'=>11),
			array('type', 'length', 'max'=>7),
			array('content', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_account_id, weixin_id, type, content, execute_time, dateline', 'safe', 'on'=>'search'),
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
			'weixin_account_id' => 'Weixin Account',
			'weixin_id' => 'Weixin',
			'type' => '日',
			'content' => '备注',
			'execute_time' => '执行时间',
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
		$criteria->compare('weixin_account_id',$this->weixin_account_id,true);
		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('execute_time',$this->execute_time);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
