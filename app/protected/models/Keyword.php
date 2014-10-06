<?php

/**
 * This is the model class for table "keyword".
 *
 * The followings are the available columns in table 'keyword':
 * @property integer $id
 * @property string $weixin_account_id
 * @property string $name
 * @property string $match
 * @property integer $ob
 * @property string $status
 * @property integer $dateline
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 * @property Material[] $materials
 */
class Keyword extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'keyword';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, weixin_account_id, name', 'required'),
			array('id, ob, dateline', 'numerical', 'integerOnly'=>true),
			array('weixin_account_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>45),
			array('match', 'length', 'max'=>7),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_account_id, name, match, ob, status, dateline', 'safe', 'on'=>'search'),
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
			'materials' => array(self::MANY_MANY, 'Material', 'keyword_relation(keyword_id, material_id)'),
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
			'name' => 'Name',
			'match' => '匹',
			'ob' => '优',
			'status' => '状态',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('weixin_account_id',$this->weixin_account_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('match',$this->match,true);
		$criteria->compare('ob',$this->ob);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('dateline',$this->dateline);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Keyword the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
