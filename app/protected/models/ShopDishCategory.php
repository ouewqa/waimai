<?php

/**
 * This is the model class for table "shop_dish_category".
 *
 * The followings are the available columns in table 'shop_dish_category':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $name
 * @property integer $ob
 * @property integer $count_items
 * @property string $status
 *
 * The followings are the available model relations:
 * @property ShopDish[] $shopDishes
 * @property WeixinAccount $weixinAccount
 */
class ShopDishCategory extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_dish_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, name', 'required'),
                array('ob, count_items', 'numerical', 'integerOnly' => true),
                array('weixin_account_id', 'length', 'max' => 11),
                array('name', 'length', 'max' => 45),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, name, ob, count_items, status', 'safe', 'on' => 'search'),
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
                'shopDishes' => array(self::HAS_MANY, 'ShopDish', 'shop_dish_category_id'),
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
                'name' => '分类名称',
                'ob' => '排序',
                'count_items' => '数量',
                'status' => '状态',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('ob', $this->ob);
        $criteria->compare('count_items', $this->count_items);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopDishCategory the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findOwerCategory($weixin_account_id, $filterEmpty = false)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = '50';
        $criteria->order = 'ob ASC';
        $criteria->condition = 'weixin_account_id=:weixin_account_id AND status=:status';
        $criteria->params = array(
                ':weixin_account_id' => $weixin_account_id,
                ':status' => 'Y',
        );

        if ($filterEmpty) {
            $criteria->addCondition('count_items>0');
        }
        return $this->findAll($criteria);
    }
}
