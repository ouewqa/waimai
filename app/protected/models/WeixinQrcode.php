<?php

/**
 * This is the model class for table "weixin_qrcode".
 *
 * The followings are the available columns in table 'weixin_qrcode':
 * @property string $id
 * @property string $weixin_account_id
 * @property string $scene_id
 * @property string $ticket
 * @property string $path
 * @property string $type
 * @property string $description
 * @property string $scan_count
 * @property integer $dateline
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinQrcode extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin_qrcode';
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
                array('dateline', 'numerical', 'integerOnly' => true),
                array('weixin_account_id, scene_id, scan_count', 'length', 'max' => 11),
                array('ticket, path, description', 'length', 'max' => 255),
                array('type', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, weixin_account_id, scene_id, ticket, path, type, description, scan_count, dateline', 'safe', 'on' => 'search'),
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
                'id' => 'scene_id 场景ID',
                'weixin_account_id' => 'Weixin Account',
                'scene_id' => '二维码场景ID',
                'ticket' => '票据',
                'path' => '物理路径',
                'type' => '类型 S系统 U用户 系统不可更改或删除 用户可更改',
                'description' => '作用描述',
                'scan_count' => '扫描统计',
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
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);
        $criteria->compare('scene_id', $this->scene_id, true);
        $criteria->compare('ticket', $this->ticket, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('scan_count', $this->scan_count, true);
        $criteria->compare('dateline', $this->dateline);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WeixinQrcode the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findBySceneId($scene_id)
    {
        return $this->find('scene_id=:scene_id', array(
                ':scene_id' => $scene_id
        ));
    }

    /**
     * 获取新的可用的scene_id
     * @param $weixin_account
     * @return int
     */
    public function getNewSceneId($weixin_account)
    {
        $model = $this->find(
                array(
                        'select' => 'scene_id',
                        'condition' => 'weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':weixin_account_id' => $weixin_account->id,
                        ),
                        'order' => 'scene_id DESC'
                )
        );

        if ($model && $model->scene_id) {
            if ($model->scene_id <= 1000) {
                $max_scene_id = 1001;
            } else {
                $max_scene_id = intval($model->scene_id) + 1;
            }


        } else {
            $max_scene_id = 1001;
        }


        #从微信服务器中获取ticket
        $weixin = new WeixinHelper($weixin_account);
        $data = $weixin->getQRTicket($max_scene_id);

        #var_dump($data);

        if ($data && $data['errcode'] == 0) {
            $ticket = $data['data']['ticket'];
            $path = $data['data']['url'];

            # echo $weixin_account->id;exit;

            $qrcode = WeixinQrcode::model()->find('weixin_account_id=:weixin_account_id AND scene_id=:scene_id', array(
                    ':weixin_account_id' => $weixin_account->id,
                    ':scene_id' => $max_scene_id,

            ));

            if (!$qrcode) {
                $qrcode = new WeixinQrcode();
                $qrcode->setAttributes(array(
                        'weixin_account_id' => $weixin_account->id,
                        'scene_id' => $max_scene_id,
                ));
            }

            $qrcode->setAttributes(array(
                    'ticket' => $ticket,
                    'path' => $path,
                    'type' => 'U',
                    'dateline' => DATELINE,
            ));

            $qrcode->save();

            return $qrcode;
        }
    }
}
