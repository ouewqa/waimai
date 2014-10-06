<?php

/**
 * This is the model class for table "site_product_order".
 *
 * The followings are the available columns in table 'site_product_order':
 * @property string $id
 * @property string $admin_id
 * @property string $site_product_id
 * @property string $order_sn
 * @property string $transaction_id
 * @property string $payment
 * @property string $buyer
 * @property string $subject
 * @property string $description
 * @property string $url
 * @property string $dateline
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Admin $admin
 * @property SiteProduct $siteProduct
 */
class SiteProductOrder extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'site_product_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('admin_id, site_product_id, payment, subject', 'required'),
                array('admin_id, site_product_id, dateline', 'length', 'max' => 11),
                array('order_sn', 'length', 'max' => 18),
                array('transaction_id', 'length', 'max' => 28),
                array('payment, buyer, subject', 'length', 'max' => 45),
                array('description, url', 'length', 'max' => 255),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, admin_id, site_product_id, order_sn, transaction_id, payment, buyer, subject, description, url, dateline, status', 'safe', 'on' => 'search'),
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
                'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
                'siteProduct' => array(self::BELONGS_TO, 'SiteProduct', 'site_product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'admin_id' => '买家ID',
                'site_product_id' => '关联产品',
                'order_sn' => '订单号',
                'transaction_id' => '交易号',
                'payment' => '支付方式',
                'buyer' => '买家账号',
                'subject' => '标题',
                'description' => '描述',
                'url' => '相关网址',
                'dateline' => '购买时间',
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
        $criteria->compare('admin_id', $this->admin_id, true);
        $criteria->compare('site_product_id', $this->site_product_id, true);
        $criteria->compare('order_sn', $this->order_sn, true);
        $criteria->compare('transaction_id', $this->transaction_id, true);
        $criteria->compare('payment', $this->payment, true);
        $criteria->compare('buyer', $this->buyer, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('dateline', $this->dateline, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SiteProductOrder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeValidate()
    {
        if (!$this->order_sn) {
            $this->order_sn = sprintf('%d%d', date('YmdHis'), rand(1000, 9999));
        }

        if (!$this->dateline) {
            $this->dateline = DATELINE;
        }
        return parent::beforeValidate();
    }

    public function findByOrderSn($order_sn)
    {
        return $this->find(
                'order_sn=:order_sn', array(
                        ':order_sn' => $order_sn,
                )
        );
    }


    /**
     * 订单成功后一些逻辑处理
     * @param $order_sn 订单号
     * @param $trade_no 交易号
     * @return bool
     */
    public static function purchased($order_sn, $trade_no)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            ###########
            $order = SiteProductOrder::model()->with('siteProduct')->findByOrderSn($order_sn);
            $admin = Admin::model()->findByPk($order->admin_id);



            if ($order && $order->status != 'Y' && $admin) {

                $result = true;

                switch ($order->siteProduct->site_product_category_id) {
                    #充值短信包
                    case 1:
                        $admin->count_sms += $order->siteProduct->number;
                        if (!$admin->save()) {
                            $result = false;
                        }
                        break;
                }

                if ($result) {
                    $order->transaction_id = $trade_no;
                    $order->status = 'Y';
                    if (!$order->save()) {
                    }
                }

            }
            ###########
            $transaction->commit();
            return $order->id;
        } catch (Exception $e) {
            return false;
        }
    }
}
