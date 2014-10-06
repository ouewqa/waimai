<?php

/**
 * This is the model class for table "shop_dish".
 *
 * The followings are the available columns in table 'shop_dish':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $shop_dish_category_id
 * @property string $name
 * @property string $image
 * @property string $price
 * @property string $discount
 * @property string $description
 * @property string $count_sales
 * @property string $count_views
 * @property integer $ob
 * @property string $dateline
 * @property string $updatetime
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Weixin[] $weixins
 * @property ShopDishCategory $shopDishCategory
 * @property WeixinAccount $weixinAccount
 * @property ShopDishAlbum[] $shopDishAlbums
 * @property ShopOrder[] $shopOrders
 * @property Weixin[] $weixins1
 */
class ShopDish extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_dish';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('weixin_account_id, shop_dish_category_id, name', 'required'),
                array('ob', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, count_sales, count_views, dateline, updatetime', 'length', 'max' => 11),
                array('shop_dish_category_id', 'length', 'max' => 10),
                array('name, discount', 'length', 'max' => 45),
                array('image', 'length', 'max' => 255),
                array('price', 'length', 'max' => 8),
                array('status', 'length', 'max' => 1),
                array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, shop_dish_category_id, name, image, price, discount, description, count_sales, count_views, ob, dateline, updatetime, status', 'safe', 'on' => 'search'),
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
                'weixins' => array(self::MANY_MANY, 'Weixin', 'favorite(shop_dish_id, weixin_id)'),
                'shopDishCategory' => array(self::BELONGS_TO, 'ShopDishCategory', 'shop_dish_category_id'),
                'weixinAccount' => array(self::BELONGS_TO, 'WeixinAccount', 'weixin_account_id'),
                'shopDishAlbums' => array(self::HAS_MANY, 'ShopDishAlbum', 'shop_dish_id'),
                'shopOrders' => array(self::MANY_MANY, 'ShopOrder', 'shop_order_item(shop_dish_id, shop_order_id)'),
                'weixins1' => array(self::MANY_MANY, 'Weixin', 'shopping_cart(shop_dish_id, weixin_id)'),
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
                'shop_dish_category_id' => '分类',
                'name' => '名称',
                'image' => '图片',
                'price' => '价格',
                'discount' => '折扣',
                'description' => '描述',
                'count_sales' => 'Count Sales',
                'count_views' => 'Count Views',
                'ob' => '排序值',
                'dateline' => 'Dateline',
                'updatetime' => 'Updatetime',
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
        $criteria->compare('shop_dish_category_id', $this->shop_dish_category_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('discount', $this->discount, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('count_sales', $this->count_sales, true);
        $criteria->compare('count_views', $this->count_views, true);
        $criteria->compare('ob', $this->ob);
        $criteria->compare('dateline', $this->dateline, true);
        $criteria->compare('updatetime', $this->updatetime, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopDish the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    protected function beforeValidate()
    {
        if ($this->price <= 0) {
            $this->addError('price', '价格不得低于0元');
        }


        return parent::beforeValidate();
    }


    protected function beforeSave()
    {
        if ($this->isNewRecord && !$this->dateline) {
            $this->dateline = DATELINE;
        }

        $this->updatetime = DATELINE;

        return parent::beforeSave();
    }

    protected function afterSave()
    {
        $model = ShopDishCategory::model()->findByPk($this->shop_dish_category_id);
        if ($model) {
            $model->count_items += 1;
            $model->save();
        }

        return parent::afterSave();
    }

    protected function afterDelete()
    {
        #删除图片
        if ($this->image) {
            ImageHelper::delete($this->image);
        }

        $model = ShopDishCategory::model()->findByPk($this->shop_dish_category_id);
        if ($model && $model->count_items >= 1) {
            $model->count_items -= 1;
            $model->save();
        }

        return parent::afterDelete();
    }

    public function findOwnerProducts($weixin_account_id)
    {
        return $this->findAll('weixin_account_id=:weixin_account_id AND status=:status', array(
                ':weixin_account_id' => $weixin_account_id,
                ':status' => 'Y',

        ));
    }
}
