<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangePassword extends CFormModel
{
    public $oldPassword;
    public $newPassword;
    public $repeatPassword;
    public $verifyCode;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
                array('oldPassword, newPassword, repeatPassword', 'required'),
                array('oldPassword, newPassword, repeatPassword', 'length', 'min' => 6, 'max' => 20),
                array('newPassword', 'compare', 'compareAttribute' => 'repeatPassword', 'on' => 'changepassword', 'message' => '两次输入不同!'),
        );
    }


    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
                'oldPassword' => '原密码',
                'newPassword' => '新密码',
                'repeatPassword' => '确认密码',
        );
    }

    public function authenticate()
    {


        $model = Admin::model()->findByPk(Yii::app()->user->id);
        /*
        echo '<pre />';
                var_dump($model->password);
                var_dump($model->salt);
                var_dump($this->oldPassword);
                var_dump($model->passwordEncrypt('123456', $model->salt));

                exit;*/


        if ($model && $model->password == $model->passwordEncrypt($this->oldPassword, $model->salt)) {
            $model->password = $model->passwordEncrypt($this->newPassword, $model->salt);
            if (!$model->save()) {
                print_r($model->getErrors());
                exit;
                $this->addError('oldPassword', '保存失败！');
            }
        } else {
            $this->addError('oldPassword', '原密码不正确！');
        }

        if ($this->hasErrors()) {
            return false;
        } else {
            return true;
        }

    }


}

 