<?php

/**
 * This is the model class for table "weixin_feedback".
 *
 * The followings are the available columns in table 'weixin_feedback':
 * @property string $id
 * @property string $weixin_id
 * @property string $weixin_account
 * @property string $email
 * @property string $image
 * @property string $feedback
 * @property string $reply
 * @property integer $dateline
 * @property integer $updatetime
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 */
class WeixinFeedback extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'weixin_feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, email, feedback', 'required'),
			array('dateline, updatetime', 'numerical', 'integerOnly'=>true),
			array('weixin_id', 'length', 'max'=>11),
			array('weixin_account, email', 'length', 'max'=>45),
			array('image', 'length', 'max'=>255),
			array('feedback, reply', 'length', 'max'=>1024),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_id, weixin_account, email, image, feedback, reply, dateline, updatetime, status', 'safe', 'on'=>'search'),
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
			'weixin_account' => '用户个人微信',
			'email' => 'Email',
			'image' => 'Image',
			'feedback' => '反馈内容',
			'reply' => '站长回复',
			'dateline' => 'Dateline',
			'updatetime' => 'Updatetime',
			'status' => 'Status',
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
		$criteria->compare('weixin_account',$this->weixin_account,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('feedback',$this->feedback,true);
		$criteria->compare('reply',$this->reply,true);
		$criteria->compare('dateline',$this->dateline);
		$criteria->compare('updatetime',$this->updatetime);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WeixinFeedback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
