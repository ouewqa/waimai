<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    public $_id;
    const ERROR_EXPIRE = 10;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */


    public function authenticate()
    {
        $model = Admin::model()->authenticate($this->username, $this->password);

        if (!$model) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else if ($model->expire <= DATELINE) {
            #账号已过期
            $this->errorCode = self::ERROR_EXPIRE;
        } else {
            #登陆后保存一些数据
            $model->setAttributes(array(
                    'last_login_time' => DATELINE,
                    'last_login_ip' => DATELINE,
                    'login_times' => $model->login_times + 1,
            ));
            $model->role = 'member';
            $model->save();

            #todo 保存一些数据至COOKIE,同时可用Yii::app()->user->XX来调用
            $this->_id = $model->id;
            Yii::app()->user->setState('role', $model->role);


            $this->errorCode = self::ERROR_NONE;
        }


        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}