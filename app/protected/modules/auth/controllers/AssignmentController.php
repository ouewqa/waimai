<?php
/**
 * AssignmentController class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2012-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package auth.controllers
 */

/**
 * Controller for assignment related actions.
 */
class AssignmentController extends AuthController
{
    /**
     * Displays the a list of all the assignments.
     */
    public function actionIndex()
    {

        $model = new Admin();

        $criteria = new CDbCriteria;
        $criteria->together = true;
        //$criteria->with = array('weixinGroup');
        $criteria->condition = 't.status=:status';
        $criteria->params = array(
                ':status' => 'Y',
        );

        if (isset($_GET)) {

        }


        $dataProvider = new CActiveDataProvider($this->module->userClass);

        $this->render('index', array(
                'model' => $model,
                'dataProvider' => $dataProvider
        ));
    }

    /**
     * Displays the assignments for the user with the given id.
     * @param string $id the user id.
     */
    public function actionView($id)
    {
        $formModel = new AddAuthItemForm();

        /* @var $am CAuthManager|AuthBehavior */
        $am = Yii::app()->getAuthManager();

        if (isset($_POST['AddAuthItemForm'])) {
            $formModel->attributes = $_POST['AddAuthItemForm'];
            if ($formModel->validate()) {


                #手动修改开始
                $transaction = Yii::app()->db->beginTransaction();
                try {

                    $authitem = Authitem::model()->find('name=:name AND type=:type', array(
                            ':name' => $formModel->items,
                            ':type' => 2,
                    ));

                    #如果是角色，即type=2
                    if ($authitem) {
                        #清空当前用户的所有角色
                        $authitems = Authitem::model()->findAll('type=2');
                        foreach ($authitems as $key => $value) {
                            $am->revoke($value->name, $id);
                        }

                        #设置用户表ADMIN中的角色值
                        $admin = Admin::model()->findByPk($id);
                        if ($admin) {
                            $admin->role = $authitem->name;
                            $admin->save();
                        }

                    }


                    if (!$am->isAssigned($formModel->items, $id)) {
                        $am->assign($formModel->items, $id);
                        if ($am instanceof CPhpAuthManager) {
                            $am->save();
                        }

                        if ($am instanceof ICachedAuthManager) {
                            $am->flushAccess($formModel->items, $id);
                        }
                    }


                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                }

                #手动修改结束


            }
        }

        $model = CActiveRecord::model($this->module->userClass)->findByPk($id);

        $assignments = $am->getAuthAssignments($id);
        $authItems = $am->getItemsPermissions(array_keys($assignments));
        $authItemDp = new AuthItemDataProvider();
        $authItemDp->setAuthItems($authItems);

        $assignmentOptions = $this->getAssignmentOptions($id);
        if (!empty($assignmentOptions)) {
            $assignmentOptions = array_merge(
                    array('' => Yii::t('AuthModule.main', 'Select item') . ' ...'),
                    $assignmentOptions
            );
        }

        $this->render(
                'view',
                array(
                        'model' => $model,
                        'authItemDp' => $authItemDp,
                        'formModel' => $formModel,
                        'assignmentOptions' => $assignmentOptions,
                )
        );
    }

    /**
     * Revokes an assignment from the given user.
     * @throws CHttpException if the request is invalid.
     */
    public function actionRevoke()
    {
        if (isset($_GET['itemName'], $_GET['userId'])) {
            $itemName = $_GET['itemName'];
            $userId = $_GET['userId'];

            /* @var $am CAuthManager|AuthBehavior */
            $am = Yii::app()->getAuthManager();

            if ($am->isAssigned($itemName, $userId)) {
                $am->revoke($itemName, $userId);

                #清空用户表ADMIN中的角色值
                $admin = Admin::model()->findByPk($userId);
                if ($admin && $admin->role == $itemName) {
                    $admin->role = null;
                    $admin->save();
                }


                if ($am instanceof CPhpAuthManager) {
                    $am->save();
                }

                if ($am instanceof ICachedAuthManager) {
                    $am->flushAccess($itemName, $userId);
                }
            }

            if (!isset($_POST['ajax'])) {
                $this->redirect(array('view', 'id' => $userId));
            }
        } else {
            throw new CHttpException(400, Yii::t('AuthModule.main', 'Invalid request.'));
        }
    }

    /**
     * Returns a list of possible assignments for the user with the given id.
     * @param string $userId the user id.
     * @return array the assignment options.
     */
    protected function getAssignmentOptions($userId)
    {
        $options = array();

        /* @var $am CAuthManager|AuthBehavior */
        $am = Yii::app()->authManager;

        $assignments = $am->getAuthAssignments($userId);
        $assignedItems = array_keys($assignments);

        /* @var $authItems CAuthItem[] */
        $authItems = $am->getAuthItems();
        foreach ($authItems as $itemName => $item) {
            if (!in_array($itemName, $assignedItems)) {
                $options[$this->capitalize($this->getItemTypeText($item->type, true))][$itemName] = $item->description;
            }
        }

        return $options;
    }
}