<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminUserIdentity extends CUserIdentity
{
    private $_id;
    public $adminUser;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
        $user = Admin::model()->find('username=:username', array(':username' => $this->username));
        if ($user) {
            $encrypted_passwd = trim($user->password);
            $inputpassword = trim(md5($this->password));
            if ($inputpassword === $encrypted_passwd) {
                $this->errorCode = self::ERROR_NONE;
                $this->setUser($user);
                $this->_id = $user->id;
                $this->username = $user->username;
                // Yii::app()->user->setState("thisisadmin", "true");
            } else {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;

            }
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }

        unset($user);
        return !$this->errorCode;

    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }

    public function setUser(CActiveRecord $user)
    {
        $this->adminUser = $user->attributes;
    }

    public function getUser()
    {
        return $this->adminUser;
    }

}