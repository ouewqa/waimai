<?php

/**
 * This is the model class for table "weixin_group".
 *
 * The followings are the available columns in table 'weixin_group':
 * @property string $id
 * @property string $weixin_account_id
 * @property integer $group_id
 * @property string $name
 * @property integer $ob
 * @property string $member_count
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinGroup extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WeixinGroup the static model class
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
        return 'weixin_group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id', 'required'),
                array('group_id, ob', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, member_count', 'length', 'max' => 11),
                array('name', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
                array('id, weixin_account_id, group_id, name, ob, member_count', 'safe', 'on' => 'search'),
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
                'group_id' => 'Group',
                'name' => 'Name',
                'ob' => 'Ob',
                'member_count' => 'Member Count',
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
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('ob', $this->ob);
        $criteria->compare('member_count', $this->member_count, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    public function getGroupList($weixin_account_id)
    {
        return $this->findAll('weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => $weixin_account_id,
        ));
    }
}