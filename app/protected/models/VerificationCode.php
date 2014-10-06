<?php

/**
 * This is the model class for table "verification_code".
 *
 * The followings are the available columns in table 'verification_code':
 * @property string $id
 * @property string $type
 * @property string $target
 * @property string $code
 * @property string $dateline
 * @property string $expire
 * @property string $status
 */
class VerificationCode extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'verification_code';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('type, target', 'required'),
                array('type, code', 'length', 'max' => 6),
                array('target', 'length', 'max' => 45),
                array('dateline, expire', 'length', 'max' => 11),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, type, target, code, dateline, expire, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'type' => 'Type',
                'target' => 'Target',
                'code' => 'Code',
                'dateline' => 'Dateline',
                'expire' => 'Expire',
                'status' => 'Status',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('target', $this->target, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('dateline', $this->dateline, true);
        $criteria->compare('expire', $this->expire, true);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VerificationCode the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 检查验证码是否用过
     * @param $type 类型　EMAIL MOBILE
     * @param $code
     * @return mixed
     */
    public function checkCode($type, $code)
    {
        return $this->find('code=:code AND type=:type AND status=:status AND expire>:expire', array(
                ':code' => $code,
                ':type' => strtolower($type),
                ':status' => 'N',
                ':expire' => time(),
        ));
    }

    /**
     * 获取验证码，不存在则生成。
     * @param $type
     * @param $target
     * @throws CHttpException
     * @return CActiveRecord
     */
    public function getCode($type, $target)
    {
        $model = $this->find('type=:type AND status=:status AND expire>:expire', array(
                ':type' => strtolower($type),
                ':status' => 'N',
                ':expire' => time(),
        ));

        if (!$model) {
            $model = new VerificationCode();
            $model->setAttributes(array(
                    'type' => $type,
                    'target' => $target,
                    'code' => rand(100000, 999999),
                    'dateline' => time(),
                    'expire' => time() + 3600 * 8,
            ));

            if (!$model->save()) {
                throw new CHttpException(500, '验证码创建失败！');
            }
        }

        return $model->code;
    }
}
