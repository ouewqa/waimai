<?php

/**
 * This is the model class for table "weixin_menu".
 *
 * The followings are the available columns in table 'weixin_menu':
 * @property string $id
 * @property string $fid
 * @property string $path
 * @property string $name
 * @property string $key
 * @property string $type
 * @property string $url
 * @property integer $ob
 * @property string $status
 * @property string $weixin_account_id
 *
 * The followings are the available model relations:
 * @property WeixinAccount $weixinAccount
 */
class WeixinMenu extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'weixin_menu';
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
                array('ob', 'numerical', 'integerOnly' => true),
                array('fid, weixin_account_id', 'length', 'max' => 11),
                array('path, key', 'length', 'max' => 45),
                array('name', 'length', 'max' => 21),
                array('type', 'length', 'max' => 5),
                array('url', 'length', 'max' => 255),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, fid, path, name, key, type, url, ob, status, weixin_account_id', 'safe', 'on' => 'search'),
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
                'fid' => '父ID',
                'path' => 'Path',
                'name' => '显示名称',
                'key' => '功能',
                'type' => '类型',
                'url' => '网址',
                'ob' => 'Ob',
                'status' => '状态',
                'weixin_account_id' => 'Weixin Account',
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
        $criteria->compare('fid', $this->fid, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('key', $this->key, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('ob', $this->ob);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('weixin_account_id', $this->weixin_account_id, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WeixinMenu the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeValidate()
    {
        $this->fid = $this->fid ? $this->fid : 0;

        if ($this->type == 'click' && empty($this->key)) {
            $this->addError('key', '菜单为点击类型，key值不得为空');
        }

        if ($this->type == 'view' && empty($this->url)) {
            $this->addError('url', '菜单为链接类型，url值不得为空');
        }

        /*if ($this->fid == 0 && $this->type == 'click') {
            $this->addError('type', '一级菜单，类型不得为点击');
        }*/

        #每个菜单下只有能５个子菜单

        if ($this->fid) {
            $count = $this->count('fid=:fid AND status=:status', array(':fid' => $this->fid, ':status' => 'Y'));

            if (($this->isNewRecord && $count >= 5) || (!$this->isNewRecord && $count > 5)) {
                $this->addError('fid', '该菜单下已有5个二级菜单了');
            }
        } else {
            $count = $this->count('fid=:fid AND weixin_account_id=:weixin_account_id AND status=:status', array(':fid' => 0, ':weixin_account_id' => $this->weixin_account_id, ':status' => 'Y'));
            if (($this->isNewRecord && $count >= 3) || (!$this->isNewRecord && $count > 3)) {
                $this->addError('fid', '只能创建3个一级菜单');
            }
        }

        return parent::beforeValidate();
    }

    /*public function afterSave()
    {


        return parent::afterSave();
    }*/

    public function makeCatepath()
    {

        if ($this->fid) {
            $m = $this->find('id=:id', array(
                    ':id' => $this->fid,
            ));

            $this->path = $this->_makeCatePath($m->path, $this->id);
        } else {
            $this->path = $this->_makeCatePath($this->id);
        }


        try {
            $this->save();

        } catch (Exception $e) {
            print_r($this->getErrors());
        }
    }

    public function _makeCatePath($a, $b = null)
    {
        /*$numargs = func_get_args();
        $result = func_num_args() == 1 ? ',' : '';
        foreach ($numargs as $key => $value) {
            $result .= $value . ',';
        }*/

        if (!$b) {
            $result = ',' . $a;
        } else {
            $result = $a . ',' . $b;
        }
        return $result;
    }

    /**
     * 获取一级根菜单
     * @param $weixin_account_id
     * @return array
     */
    public function getFirstLevelMenu($weixin_account_id)
    {
        $model = $this->findAll('fid=0 AND weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => $weixin_account_id,
        ));
        $data = array();
        foreach ($model as $key => $value) {
            $data[] = array(
                    'id' => $value->id,
                    'name' => $value->name,
            );
        }

        return $data;
    }
}
