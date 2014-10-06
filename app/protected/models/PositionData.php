<?php

/**
 * This is the model class for table "position_data".
 *
 * The followings are the available columns in table 'position_data':
 * @property string $id
 * @property string $position_id
 * @property string $text
 * @property string $description
 * @property string $link
 * @property string $image
 * @property string $thumb
 * @property string $extra
 * @property integer $ob
 * @property string $sign
 * @property integer $new_window
 * @property string $status
 * @property string $expiration
 * @property string $dateline
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Position $position
 */
class PositionData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'position_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('position_id', 'required'),
			array('ob, new_window', 'numerical', 'integerOnly'=>true),
			array('position_id, sign, expiration, dateline', 'length', 'max'=>11),
			array('text, type', 'length', 'max'=>45),
			array('description', 'length', 'max'=>1024),
			array('link, image, thumb', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('extra', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, position_id, text, description, link, image, thumb, extra, ob, sign, new_window, status, expiration, dateline, type', 'safe', 'on'=>'search'),
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
			'position' => array(self::BELONGS_TO, 'Position', 'position_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'position_id' => 'Position',
			'text' => '文字',
			'description' => '描述',
			'link' => '链接',
			'image' => '图片',
			'thumb' => '缩略图',
			'extra' => '额外数据',
			'ob' => '排序值　越小越靠前',
			'sign' => '标识（判断是否已经存在标识）',
			'new_window' => '弹新窗',
			'status' => 'Status',
			'expiration' => 'Expiration',
			'dateline' => '提交时间',
			'type' => 'A Article',
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
		$criteria->compare('position_id',$this->position_id,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('thumb',$this->thumb,true);
		$criteria->compare('extra',$this->extra,true);
		$criteria->compare('ob',$this->ob);
		$criteria->compare('sign',$this->sign,true);
		$criteria->compare('new_window',$this->new_window);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('expiration',$this->expiration,true);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PositionData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
