<?php

/**
 * This is the model class for table "weixin_command".
 *
 * The followings are the available columns in table 'weixin_command':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $source
 * @property string $command
 * @property string $match
 * @property string $description
 * @property string $type
 * @property string $value
 * @property string $status
 * @property string $ob
 * @property string $cost
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 * @property WeixinCommandLog[] $weixinCommandLogs
 */
class WeixinCommand extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_command';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_account_id', 'required'),
			array('weixin_account_id, ob, cost', 'length', 'max'=>11),
			array('source, command', 'length', 'max'=>45),
			array('match', 'length', 'max'=>7),
			array('description', 'length', 'max'=>1024),
			array('type', 'length', 'max'=>9),
			array('value', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_account_id, source, command, match, description, type, value, status, ob, cost', 'safe', 'on'=>'search'),
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
			'weixinCommandLogs' => array(self::HAS_MANY, 'WeixinCommandLog', 'weixin_command_id'),
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
			'source' => '来源',
			'command' => '命令',
			'match' => '匹',
			'description' => '指令描述',
			'type' => 'flow ',
			'value' => '关联type的值',
			'status' => '状态',
			'ob' => '优',
			'cost' => '消费金币',
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
		$criteria->compare('source',$this->source,true);
		$criteria->compare('command',$this->command,true);
		$criteria->compare('match',$this->match,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('ob',$this->ob,true);
		$criteria->compare('cost',$this->cost,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinCommand the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
