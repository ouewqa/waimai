<?php

/**
 * This is the model class for table "shop_dish_album".
 *
 * The followings are the available columns in table 'shop_dish_album':
 * @property integer $id
 * @property string $shop_dish_id
 * @property string $image
 * @property string $description
 *
 * The followings are the available model relations:
 * @property ShopDish $shopDish
 */
class ShopDishAlbum extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_dish_album';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('shop_dish_id', 'required'),
                array('shop_dish_id', 'length', 'max' => 10),
                array('image', 'length', 'max' => 255),
                array('description', 'length', 'max' => 1024),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, shop_dish_id, image, description', 'safe', 'on' => 'search'),
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
                'shopDish' => array(self::BELONGS_TO, 'ShopDish', 'shop_dish_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'shop_dish_id' => 'Shop Dish',
                'image' => '图片地址',
                'description' => '描述',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('shop_dish_id', $this->shop_dish_id, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopDishAlbum the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function afterDelete()
    {
        if ($this->image) {
            ImageHelper::delete($this->image);
        }
        return parent::afterDelete();
    }


}
