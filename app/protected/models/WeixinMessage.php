<?php

/**
 * This is the model class for table "weixin_message".
 *
 * The followings are the available columns in table 'weixin_message':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $weixin_id
 * @property string $message
 * @property integer $flag
 * @property string $type
 * @property string $dateline
 * @property string $status
 * @property string $weixin_attachment_id
 * @property string $io
 *
 * The followings are the available model relations:
 * @property Weixin $weixin
 * @property WeixinAccount $weixinAccount
 */
class WeixinMessage extends CActiveRecord
{
    public $music_title,
            $music_description,
            $music_url;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WeixinMessage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin_message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, weixin_id', 'required'),
                array('flag', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, weixin_id, dateline, weixin_attachment_id', 'length', 'max' => 11),
                array('message', 'length', 'max' => 2048),
                array('music_title, music_description, music_url', 'length', 'max' => 2048),
                array('type', 'length', 'max' => 8),
                array('status, io', 'length', 'max' => 1),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
                array('id, weixin_account_id, weixin_id, message, flag, type, dateline, status, weixin_attachment_id, io', 'safe', 'on' => 'search'),
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
                'weixin_id' => 'Weixin',
                'message' => 'Message',
                'flag' => 'Flag',
                'type' => 'Type',
                'dateline' => 'Dateline',
                'status' => 'Status',
                'weixin_attachment_id' => 'Weixin Attachment',
                'io' => 'Io',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);
        $criteria->compare('weixin_id', $this->weixin_id, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('flag', $this->flag);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('dateline', $this->dateline, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('weixin_attachment_id', $this->weixin_attachment_id, true);
        $criteria->compare('io', $this->io, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    public function findTopic($weixin_id, $limit = 30)
    {
        return $this->findAll(
                array(
                        'condition' => 'weixin_id=:weixin_id',
                        'params' => array(
                                ':weixin_id' => $weixin_id,
                        ),
                        'limit' => $limit,
                        'order' => 'dateline DESC',
                )
        );
    }
}