<?php

/**
 * This is the model class for table "material".
 *
 * The followings are the available columns in table 'material':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $type
 * @property string $keyword
 * @property string $title
 * @property string $image
 * @property string $description
 * @property string $content
 * @property string $url
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class Material extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'material';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, keyword, description', 'required'),
                array('weixin_account_id', 'length', 'max' => 11),
                array('type', 'length', 'max' => 1),
                array('keyword, title', 'length', 'max' => 45),
                array('image, url', 'length', 'max' => 255),
                array('description', 'length', 'max' => 1024),
                array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, type, keyword, title, image, description, content, url', 'safe', 'on' => 'search'),
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
                'type' => '类型',
                'keyword' => '关键字',
                'title' => '标题',
                'image' => '图片',
                'description' => '介绍',
                'content' => '内容',
                'url' => '网址',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('keyword', $this->keyword, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('url', $this->url, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Material the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
