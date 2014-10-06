<?php

/**
 * This is the model class for table "feedback".
 *
 * The followings are the available columns in table 'feedback':
 * @property string $id
 * @property string $weixin_id
 * @property string $shop_id
 * @property string $weixin_account
 * @property string $mobile
 * @property string $email
 * @property string $image
 * @property string $content
 * @property string $reply
 * @property integer $dateline
 * @property integer $reply_time
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 * @property Shop $shop
 */
class Feedback extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weixin_id, shop_id, content', 'required'),
			array('dateline, reply_time', 'numerical', 'integerOnly'=>true),
			array('weixin_id, shop_id, mobile', 'length', 'max'=>11),
			array('weixin_account, email', 'length', 'max'=>45),
			array('image', 'length', 'max'=>255),
			array('content, reply', 'length', 'max'=>1024),

            #手动修改
                array('mobile', 'length', 'min' => 11, 'max' => 11, 'message' => '手机号码不正确'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, weixin_id, shop_id, weixin_account, mobile, email, image, content, reply, dateline, reply_time', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
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
			'shop_id' => '所属店铺',
			'weixin_account' => '用户个人微信',
			'mobile' => '手机号码',
			'email' => '电子邮箱',
			'image' => 'Image',
			'content' => '反馈内容',
			'reply' => '站长回复',
			'dateline' => 'Dateline',
			'reply_time' => 'Reply Time',
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
		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('weixin_account',$this->weixin_account,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('reply',$this->reply,true);
		$criteria->compare('dateline',$this->dateline);
		$criteria->compare('reply_time',$this->reply_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Feedback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    protected function beforeValidate()
    {
        if (!$this->weixin_account || !$this->mobile) {
            $this->addError('weixin_account', '微信账号与手机号码必须填写一个');
        }
        return parent::beforeValidate();
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord || !$this->dateline) {
            $this->dateline = DATELINE;
        } else if (!$this->isNewRecord) {
            $this->reply_time = DATELINE;
        }
        return parent::beforeSave();
    }
}
