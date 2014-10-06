<?php

/**
 * This is the model class for table "cate_area".
 *
 * The followings are the available columns in table 'cate_area':
 * @property string $id
 * @property string $pid
 * @property string $name
 * @property string $path
 * @property integer $level
 * @property string $status
 */
class CateArea extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cate_area';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('name', 'required'),
                array('level', 'numerical', 'integerOnly' => true),
                array('pid', 'length', 'max' => 11),
                array('name', 'length', 'max' => 45),
                array('path', 'length', 'max' => 200),
                array('status', 'length', 'max' => 1),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, pid, name, path, level, status', 'safe', 'on' => 'search'),
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
                'pid' => 'Pid',
                'name' => 'Name',
                'path' => 'Path',
                'level' => '节点层次',
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
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('path', $this->path, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CateArea the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function getProvinceList()
    {
        return CateArea::model()->cache(86400)->findAll('pid IS NULL');
    }

    /**
     * 提供省名，返回市名
     * @param $name 省名
     * @return CActiveRecord[]
     */
    public function getCityList($name)
    {
        $model = CateArea::model()->cache(86400)->find('name=:name AND level=:level', array(
                ':name' => $name,
                ':level' => 1,
        ));
        if ($model) {
            return CateArea::model()->cache(86400)->findAllByAttributes(array('pid' => $model->id));
        }

    }


    /**
     * 提供市名，返回区名
     * @param $name
     * @return array
     */
    public function getDistrictList($name)
    {
        $model = CateArea::model()->cache(86400)->find('name=:name AND level=:level', array(
                ':name' => $name,
                ':level' => 2,
        ));

        if ($model) {
            return CateArea::model()->cache(86400)->findAllByAttributes(array('pid' => $model->id));
        }
    }
}
