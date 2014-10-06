<?php

/**
 * This is the model class for table "announcement".
 *
 * The followings are the available columns in table 'announcement':
 * @property string $id
 * @property string $shop_id
 * @property string $content
 * @property string $dateline
 * @property integer $expire
 *
 * The followings are the available model relations:
 * @property Shop $shop
 */
class Announcement extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'announcement';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('shop_id, content', 'required'),
                array('expire', 'numerical', 'integerOnly' => true),
                array('shop_id, dateline', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, shop_id, content, dateline, expire', 'safe', 'on' => 'search'),
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
                'shop_id' => '公告所属店铺',
                'content' => '公告内容',
                'dateline' => '发布时间',
                'expire' => '过期时间',
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
        $criteria->compare('shop_id', $this->shop_id, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('dateline', $this->dateline, true);
        $criteria->compare('expire', $this->expire);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Announcement the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeValidate()
    {

        if ($this->expire && strpos($this->expire, '-') !== false) {
            $this->expire = strtotime($this->expire);
        }

        if ($this->dateline && strpos($this->dateline, '-') !== false) {
            $this->dateline = strtotime($this->dateline);
        }

        if ($this->isNewRecord && !$this->dateline) {
            $this->dateline = DATELINE;
        }

        return parent::beforeValidate();
    }

    public function findOwnersAnnouncement($shop_id)
    {
        return $this->cache(86400)->find('shop_id=:shop_id AND dateline<:dateline AND expire IS NULL OR expire>:expire', array(
                ':shop_id' => $shop_id,
                ':dateline' => DATELINE,
                ':expire' => DATELINE,
        ));
    }
}
