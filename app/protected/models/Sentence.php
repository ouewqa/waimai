<?php

/**
 * This is the model class for table "sentence".
 *
 * The followings are the available columns in table 'sentence':
 * @property string $id
 * @property string $word_meaning_id
 * @property string $weixin_id
 * @property string $sentence
 * @property string $meaning
 * @property string $hiragara
 * @property string $dateline
 * @property integer $tts_id
 */
class Sentence extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sentence';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('word_meaning_id, weixin_id, sentence, meaning', 'required'),
			array('tts_id', 'numerical', 'integerOnly'=>true),
			array('word_meaning_id, weixin_id, dateline', 'length', 'max'=>11),
			array('sentence, meaning', 'length', 'max'=>255),
			array('hiragara', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, word_meaning_id, weixin_id, sentence, meaning, hiragara, dateline, tts_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'word_meaning_id' => 'Word Meaning',
			'weixin_id' => 'Weixin',
			'sentence' => 'Sentence',
			'meaning' => 'Meaning',
			'hiragara' => 'Hiragara',
			'dateline' => 'Dateline',
			'tts_id' => 'TTS id',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('word_meaning_id',$this->word_meaning_id,true);
		$criteria->compare('weixin_id',$this->weixin_id,true);
		$criteria->compare('sentence',$this->sentence,true);
		$criteria->compare('meaning',$this->meaning,true);
		$criteria->compare('hiragara',$this->hiragara,true);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('tts_id',$this->tts_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sentence the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
