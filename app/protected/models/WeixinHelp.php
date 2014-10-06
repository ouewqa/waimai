<?php

/**
 * This is the model class for table "weixin_help".
 *
 * The followings are the available columns in table 'weixin_help':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $title
 * @property string $content
 * @property string $status
 * @property integer $ob
 * @property string $view_count
 * @property string $redirect
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinHelp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_help';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_account_id, content', 'required'),
			array('ob', 'numerical', 'integerOnly'=>true),
			array('weixin_account_id, view_count, dateline', 'length', 'max'=>11),
			array('title', 'length', 'max'=>45),
			array('content', 'length', 'max'=>1024),
			array('status', 'length', 'max'=>1),
			array('redirect', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_account_id, title, content, status, ob, view_count, redirect, dateline', 'safe', 'on'=>'search'),
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
			'title' => '公告标题',
			'content' => '公告内容',
			'status' => '公告状态',
			'ob' => 'Ob',
			'view_count' => 'View Count',
			'redirect' => 'Redirect',
			'dateline' => '发布时间',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('ob',$this->ob);
		$criteria->compare('view_count',$this->view_count,true);
		$criteria->compare('redirect',$this->redirect,true);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinHelp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
