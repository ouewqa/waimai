<?php

/**
 * This is the model class for table "weixin_media".
 *
 * The followings are the available columns in table 'weixin_media':
 * @property string $id
 * @property string $weixin_id
 * @property string $type
 * @property string $media_id
 * @property string $recognition
 * @property string $path
 * @property string $mp3
 * @property string $dateline
 * @property string $expire
 * @property string $status
 * @property string $count_use
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 */
class WeixinMedia extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, type', 'required'),
			array('weixin_id, dateline, expire, count_use', 'length', 'max'=>11),
			array('type', 'length', 'max'=>5),
			array('media_id', 'length', 'max'=>64),
			array('path, mp3', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('recognition', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_id, type, media_id, recognition, path, mp3, dateline, expire, status, count_use', 'safe', 'on'=>'search'),
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
			'media_id' => 'Media',
			'recognition' => '语音识别内容',
			'path' => 'Path',
			'mp3' => 'Mp3',
			'dateline' => 'Dateline',
			'expire' => 'Expire',
			'status' => 'Status',
			'count_use' => '使用次数',
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
		$criteria->compare('media_id',$this->media_id,true);
		$criteria->compare('recognition',$this->recognition,true);
		$criteria->compare('path',$this->path,true);
		$criteria->compare('mp3',$this->mp3,true);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('expire',$this->expire,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('count_use',$this->count_use,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinMedia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
