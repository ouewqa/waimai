<?php

/**
 * This is the model class for table "site_product".
 *
 * The followings are the available columns in table 'site_product':
 * @property string $id
 * @property string $site_product_category_id
 * @property string $name
 * @property string $price
 * @property integer $number
 * @property string $description
 * @property string $image
 * @property string $content
 * @property string $status
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property SiteProductCategory $siteProductCategory
 * @property SiteProductOrder[] $siteProductOrders
 */
class SiteProduct extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'site_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_product_category_id, name, description', 'required'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('site_product_category_id, dateline', 'length', 'max'=>11),
			array('name', 'length', 'max'=>45),
			array('price', 'length', 'max'=>10),
			array('description, image', 'length', 'max'=>255),
			array('status', 'length', 'max'=>1),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, site_product_category_id, name, price, number, description, image, content, status, dateline', 'safe', 'on'=>'search'),
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
			'siteProductCategory' => array(self::BELONGS_TO, 'SiteProductCategory', 'site_product_category_id'),
			'siteProductOrders' => array(self::HAS_MANY, 'SiteProductOrder', 'site_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'site_product_category_id' => 'Site Product Category',
			'name' => 'Name',
			'price' => '单价',
			'number' => '数量',
			'description' => 'Description',
			'image' => 'Image',
			'content' => 'Content',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('site_product_category_id',$this->site_product_category_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiteProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
