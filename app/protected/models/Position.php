<?php

/**
 * This is the model class for table "position".
 *
 * The followings are the available columns in table 'position':
 * @property string $id
 * @property string $module
 * @property string $sign
 * @property string $name
 * @property integer $maxnum
 * @property integer $ob
 * @property string $image
 * @property string $template
 * @property string $system
 *
 * The followings are the available model relations:
 * @property PositionData[] $positionDatas
 */
class Position extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'position';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maxnum, ob', 'numerical', 'integerOnly'=>true),
			array('module, sign, name, template', 'length', 'max'=>45),
			array('image', 'length', 'max'=>255),
			array('system', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, module, sign, name, maxnum, ob, image, template, system', 'safe', 'on'=>'search'),
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
			'positionDatas' => array(self::HAS_MANY, 'PositionData', 'position_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'module' => '所属模块',
			'sign' => '英文标识',
			'name' => '推荐位名称',
			'maxnum' => 'Maxnum',
			'ob' => '排序，越小越靠前',
			'image' => '图片，演示推荐位所在的位置',
			'template' => '推荐位模板',
			'system' => '是否为系统内部用',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('sign',$this->sign,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('maxnum',$this->maxnum);
		$criteria->compare('ob',$this->ob);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('system',$this->system,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Position the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
