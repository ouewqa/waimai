<?php

/**
 * This is the model class for table "shop_order".
 *
 * The followings are the available columns in table 'shop_order':
 * @property string $id
 * @property string $shop_id
 * @property string $scene_id
 * @property string $weixin_id
 * @property string $used_addresses_id
 * @property string $delivery_time
 * @property string $comment
 * @property string $image
 * @property string $payment_method_id
 * @property string $money
 * @property string $paid
 * @property integer $status
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property PaymentMethod $paymentMethod
 * @property Shop $shop
 * @property UsedAddresses $usedAddresses
 * @property Weixin $weixin
 * @property ShopDish[] $shopDishes
 */
class ShopOrder extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('shop_id, weixin_id, used_addresses_id, payment_method_id', 'required'),
                array('status', 'numerical', 'integerOnly' => true),
                array('shop_id, scene_id, weixin_id, used_addresses_id, payment_method_id, dateline', 'length', 'max' => 11),
                array('delivery_time', 'length', 'max' => 45),
                array('comment, image', 'length', 'max' => 255),
                array('money', 'length', 'max' => 8),
                array('paid', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, shop_id, scene_id, weixin_id, used_addresses_id, delivery_time, comment, image, payment_method_id, money, paid, status, dateline', 'safe', 'on' => 'search'),
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
                'paymentMethod' => array(self::BELONGS_TO, 'PaymentMethod', 'payment_method_id'),
                'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
                'usedAddresses' => array(self::BELONGS_TO, 'UsedAddresses', 'used_addresses_id'),
                'weixin' => array(self::BELONGS_TO, 'Weixin', 'weixin_id'),
                'shopDishes' => array(self::MANY_MANY, 'ShopDish', 'shop_order_item(shop_order_id, shop_dish_id)'),

            #手动添加
                'items' => array(self::HAS_MANY, 'ShopOrderItem', 'shop_order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'shop_id' => 'Shop',
                'scene_id' => '二维码场景ID',
                'weixin_id' => 'Weixin',
                'used_addresses_id' => '送餐地址',
                'delivery_time' => '送餐时间',
                'comment' => '订单留言',
                'image' => '订单图片',
                'payment_method_id' => '支付方式',
                'money' => '金额',
                'paid' => '支付状态',
                'status' => '状态',
                'dateline' => 'Dateline',
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
        $criteria->compare('scene_id', $this->scene_id, true);
        $criteria->compare('weixin_id', $this->weixin_id, true);
        $criteria->compare('used_addresses_id', $this->used_addresses_id, true);
        $criteria->compare('delivery_time', $this->delivery_time, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('payment_method_id', $this->payment_method_id, true);
        $criteria->compare('money', $this->money, true);
        $criteria->compare('paid', $this->paid, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('dateline', $this->dateline, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShopOrder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord || !$this->dateline) {
            $this->dateline = time();
        }
        return parent::beforeSave();
    }

    /**
     * 找到二维码场景绑定的订单
     * @param $scene_id
     * @return CActiveRecord
     */
    public function findBySceneId($scene_id)
    {
        return $this->find(
                'scene_id=:scene_id', array(
                        ':scene_id' => $scene_id,
                )
        );
    }

    /**
     * 状态统计
     * @param $status
     * @param $shop_id
     * @return string
     */
    public function countStatus($shop_id, $status)
    {

        $dependecy = new CDbCacheDependency('SELECT MAX(id) FROM `' . $this->tableName() . '` WHERE shop_id="' . $shop_id . '"');

        return $this->cache(86400, $dependecy)->count(array(
                'select' => 'id',
                'condition' => 'shop_id=:shop_id AND status=:status',
                'params' => array(
                        ':shop_id' => $shop_id,
                        ':status' => $status,
                )
        ));
    }

    /**
     * 构建打印数据消息
     * @param ShopOrder $model
     * @param Shop $shop
     * @throws CHttpException
     * @return array
     */
    public function buildPrintData(ShopOrder $model, Shop $shop)
    {


        if (!$shop || !$model) {
            throw new CHttpException(400, '不存在');
        }
        $address = UsedAddresses::model()->findByPk($model->used_addresses_id);
        $items = ShopOrderItem::model()->with('shopDish')->findAll('shop_order_id=:shop_order_id', array(
                ':shop_order_id' => $model->id,
        ));


        $printData = array(
                'name' => $shop->name,
                'comment' => $model->comment,
                'address' => $address->district . $address->address . $address->realname,
                'mobile' => $address->mobile,
                'dateline' => date('Y-m-d H:i:s'),
                'money' => $model->money . ' 元' .
                        "<BR>--------------------------------<BR>订单号：" . $model->id .
                        "<BR>用户ID：" . $model->weixin_id .
                        "<BR>送餐时间：" .
                        $model->delivery_time .
                        "<BR>付款方式：" . $model->paymentMethod->name,
                'qrcode' => '',
        );

        $printData['dateline'] .= '<BR>--------------------------------<BR>本店地址：' .
                $shop->address .
                '<BR>本店电话：' . $shop->telephone .
                '<BR>--------------------------------';

        if ($model->scene_id) {
            $qrCode = WeixinQrcode::model()->findBySceneId($model->scene_id);
            if ($qrCode) {
                $printData['dateline'] .= '<BR>扫描以下二维码，即可确认收货。';
                $printData['qrcode'] = $qrCode->path;
            }
        }


        $temp_money = 0;
        foreach ($items as $item) {

            $printData['items'][] = array(
                    'name' => $item->shopDish->name,
                    'price' => $item->shopDish->price,
                    'number' => $item->number,
                    'money' => intval($item->shopDish->price) * $item->number,
            );

            $temp_money += intval($item->shopDish->price) * $item->number;
        }


        if ($temp_money < $shop->minimum_charge) {
            $printData['items'][] = array(
                    'name' => '送餐费',
                    'price' => $shop->express_fee,
                    'number' => 1,
                    'money' => $shop->express_fee,
            );
        }

        return $printData;
    }
}
