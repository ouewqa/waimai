<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property string $id
 * @property string $role
 * @property string $username
 * @property string $password
 * @property string $repassword
 * @property string $mobile
 * @property string $email
 * @property string $mobile_is_verify
 * @property string $email_is_verify
 * @property integer $count_sms
 * @property integer $count_email
 * @property string $regist_ip
 * @property string $regist_time
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property string $login_times
 * @property string $salt
 * @property integer $expire
 * @property string $status
 *
 * The followings are the available model relations:
 * @property WeixinAccount[] $weixinAccounts
 */
class Admin extends CActiveRecord
{

    public $repassword;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'admin';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('username, password, mobile, salt', 'required'),
                array('count_sms, count_email, expire', 'numerical', 'integerOnly' => true),
                array('role, username, email', 'length', 'max' => 45),
                array('password', 'length', 'max' => 32),
                array('mobile, regist_time, last_login_time, login_times', 'length', 'max' => 11),
                array('mobile_is_verify, email_is_verify, status', 'length', 'max' => 1),
                array('regist_ip, last_login_ip', 'length', 'max' => 15),
                array('salt', 'length', 'max' => 6),


            #手动添加
                array('email', 'email'),
                array('username', 'length', 'min' => 4, 'max' => 20),
                array('password, repassword', 'length', 'min' => 6, 'max' => 32),
                array('mobile', 'length', 'min' => 11, 'max' => 11, 'message' => '手机号码不正确'),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
                array('id, role, username, password, mobile, email, mobile_is_verify, email_is_verify, count_sms, count_email, regist_ip, regist_time, last_login_time, last_login_ip, login_times, salt, expire, status', 'safe', 'on' => 'search'),
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
                'weixinAccounts' => array(self::HAS_MANY, 'WeixinAccount', 'admin_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'role' => 'Role',
                'username' => '用户名',
                'password' => '密码',
                'repassword' => '确认密码',
                'mobile' => '手机号码',
                'email' => '邮件',
                'mobile_is_verify' => 'Mobile Is Verify',
                'email_is_verify' => 'W waiting',
                'count_sms' => 'Count Sms',
                'count_email' => 'Count Email',
                'regist_ip' => 'Regist Ip',
                'regist_time' => 'Regist Time',
                'last_login_time' => 'Last Login Time',
                'last_login_ip' => 'Last Login Ip',
                'login_times' => 'Login Times',
                'salt' => '密码salt',
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
        $criteria->compare('role', $this->role, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('mobile_is_verify', $this->mobile_is_verify, true);
        $criteria->compare('email_is_verify', $this->email_is_verify, true);
        $criteria->compare('count_sms', $this->count_sms);
        $criteria->compare('count_email', $this->count_email);
        $criteria->compare('regist_ip', $this->regist_ip, true);
        $criteria->compare('regist_time', $this->regist_time, true);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('last_login_ip', $this->last_login_ip, true);
        $criteria->compare('login_times', $this->login_times, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('expire', $this->expire);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Admin the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function beforeValidate()
    {
        if ($this->isNewRecord) {

            if ($this->username && $this->find('username=:username', array(
                            ':username' => $this->username
                    ))
            ) {
                $this->addError('username', '用户名已存在！');
            }

            if ($this->email && $this->find('email=:email', array(
                            ':email' => $this->email
                    ))
            ) {
                $this->addError('email', '邮件地址已存在！');
            }

            if ($this->mobile && $this->find('mobile=:mobile', array(
                            ':mobile' => $this->mobile
                    ))
            ) {
                $this->addError('mobile', '手机号码已存在！');
            }

            if ($this->password !== $this->repassword) {
                $this->addError('password', '密码与二次确认密码不一致！' . $this->password . ',' . $this->repassword);
            }
        } else {
            #判断邮件是否重复
            if ($this->email && $this->find('email=:email AND id!=:id', array(
                            ':email' => $this->email,
                            ':id' => Yii::app()->user->id,
                    ))
            ) {
                $this->addError('email', '邮件地址已存在！');
            }
        }

        return parent::beforeValidate();
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->password = $this->passwordEncrypt($this->password, $this->salt);
            $this->regist_ip = Yii::app()->request->userHostAddress;
            $this->regist_time = time();
            $this->expire = time() + 86400 * 30;
        } else {


        }

        return parent::beforeSave();
    }


    protected function afterSave()
    {
        if ($this->isNewRecord) {
            #发送邮件验证通知
            if ($this->email_is_verify == 'N') {

            }

            #发送短信验证通知
            if ($this->mobile_is_verify == 'N') {

            }
        } else {

        }

        return parent::afterSave();
    }


    public function authenticate($username, $password)
    {
        $model = $this->find('username=:username', array(
                ':username' => $username,
        ));

        if ($model && $model->password == $model->passwordEncrypt($password, $model->salt)) {
            return $model;
        } else {
            return false;
        }
    }

    #获取随机字符
    public function  genRandomString($len)
    {
        $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
                "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
                "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
                "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
                "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    public function passwordEncrypt($password, $salt)
    {
        return md5($password . $salt);
    }


    /**
     * 发送邮件验证码
     * @param null $target
     * @return bool
     */
    public function sendEmailVerify($target = null)
    {
        $target = $target ? $target : $this->email;
        $code = VerificationCode::model()->getCode('email', $target);
        return EmailHelper::send(array(
                'email' => $target,
                'subject' => $this->username . '，你的邮箱验证码。',
                'data' => array(
                        'title' => '邮箱验证码',
                        'content' => '验证码：' . $code,
                ),
                'view' => 'email',
                'debug' => false,
        ));
    }

    public function sendMobileVerify($target = null)
    {
        $target = $target ? $target : $this->mobile;

        $code = VerificationCode::model()->getCode('mobile', $target);
        return true;
    }
}
