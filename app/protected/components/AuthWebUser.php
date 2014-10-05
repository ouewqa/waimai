<?php
/**
 * AuthWebUser class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package auth.components
 */

/**
 * Web user that allows for passing access checks when enlisted as an administrator.
 *
 * @property boolean $isAdmin whether the user is an administrator.
 */
class AuthWebUser extends CWebUser
{
    public $logoutUrl = array('/member/public/logout');
    public $loginUrl = array('/member/public/login');

    /**
     * @var string[] a list of names for the users that should be treated as administrators.
     */
    public $admins = array('admin');

    /**
     * Initializes the component.
     */
    public function init()
    {
        parent::init();
        $this->setIsAdmin(in_array($this->name, $this->admins));
    }

    /*public function getId(){

    }*/

    /**
     * Returns whether the logged in user is an administrator.
     * @return boolean the result.
     */
    public function getIsAdmin()
    {
        return $this->getState('__isAdmin', false);
    }

    /**
     * Sets the logged in user as an administrator.
     * @param boolean $value whether the user is an administrator.
     */
    public function setIsAdmin($value)
    {
        $this->setState('__isAdmin', $value);
    }

    /**
     * Performs access check for this user.
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * with the tasks and roles assigned to the user.
     * @param boolean $allowCaching whether to allow caching the result of access check.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($params = array(), $operation = null, $allowCaching = true)
    {
        if ($this->isGuest) {
            Yii::app()->user->setReturnUrl(Yii::app()->request->url);
            $this->loginRequired();
        }

        if ($this->getIsAdmin()) {
            return true;
        }


        $operation = $operation ? $operation : $operation = sprintf('%s.%s.%s',
                Yii::app()->controller->module->id,
                Yii::app()->controller->id,
                Yii::app()->controller->action->id
        );

        #echo Yii::app()->user->role;exit;

        $params = CMap::mergeArray(array(
                'role' => Yii::app()->user->role,
        ), $params);

        #print_r($params);exit;


        #如果rbac中没有这个动作，允许他通过，系统默认为不通过。
        $sql = "SELECT * FROM `authitem` WHERE `name`='{$operation}'";
        $operationExist = Yii::app()->db->createCommand($sql)->queryAll();

        //var_dump($operationExist);exit;

        if (!$operationExist) {
            return true;
        }

        return parent::checkAccess($operation, $params, $allowCaching);
    }
}
