<?php

/**
 * This is the model class for table "share_log".
 *
 * The followings are the available columns in table 'share_log':
 * @property string $id
 * @property string $weixin_id
 * @property string $type
 * @property string $title
 * @property string $url
 * @property string $count_view
 * @property string $sign
 * @property integer $dateline
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 */
class ShareLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'share_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, url, sign', 'required'),
			array('dateline', 'numerical', 'integerOnly'=>true),
			array('weixin_id, count_view', 'length', 'max'=>11),
			array('type', 'length', 'max'=>45),
			array('title, url', 'length', 'max'=>255),
			array('sign', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_id, type, title, url, count_view, sign, dateline', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'title' => 'Title',
			'url' => 'Url',
			'count_view' => '统计被查看的人数',
			'sign' => '对URL进行MD5,方便搜索',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('count_view',$this->count_view,true);
		$criteria->compare('sign',$this->sign,true);
		$criteria->compare('dateline',$this->dateline);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShareLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
