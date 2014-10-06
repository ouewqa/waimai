<?php

class WeixinGroupController extends BackendController
{


    protected function beforeAction($action)
    {
        parent::beforeAction($action);

        if ($this->account->advanced_interface != 'Y') {
            $this->error('微信会员分组需要高级接口，请先通过微信申请高级接口', array(
                    '/member/weixinAccount/update', 'id' => $this->account->id
            ));
        }


        return true;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
                'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new WeixinGroup;
        $model->weixin_account_id = $this->account->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WeixinGroup'])) {
            $model->attributes = $_POST['WeixinGroup'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
                'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->weixin_account_id = $this->account->id;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WeixinGroup'])) {
            $model->attributes = $_POST['WeixinGroup'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->setPageTitle('更新分组');

        $this->render('update', array(
                'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('WeixinGroup', array(
                'criteria' => array(
                        'condition' => 'weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':weixin_account_id' => $this->account->id,
                        ),

                ),
                'pagination' => array(
                        'pageSize' => 100,
                ),
        ));

        $this->setPageTitle('会员分组管理');


        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new WeixinGroup('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['WeixinGroup']))
            $model->attributes = $_GET['WeixinGroup'];

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WeixinGroup the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = WeixinGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WeixinGroup $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'weixin-group-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSync()
    {
        $weixin = new WeixinHelper($this->account);

        $result = $weixin->getGroup();

        if (!$result['errcode']) {
            $transaction = Yii::app()->db->beginTransaction();

            try {
                /*$model = new WeixinGroup();
                $model->deleteAll(
                        'weixin_account_id=:weixin_account_id', array(
                                ':weixin_account_id' => $this->account->id,
                        )
                );

                $sql = '';*/
                $group_ids = array();
                foreach ($result['data']['groups'] as $key => $value) {

                    //$sql .= 'INSERT INTO ' . $model->tableName() . "(`group_id`, `name`, `member_count`, `weixin_account_id`) VALUES ('{$value['id']}','{$value['name']}','{$value['count']}',  '{$this->account->id}');";
                    /*$groups[] = array(
                        'group_id' => $value['id'],
                        'name' => $value['name'],
                        'member_count' => $value['count'],
                        'weixin_account_id' => $this->account->id,
                    );*/
                    $group_ids[] = $value['id'];
                    $group = WeixinGroup::model()->find(
                            'group_id=:group_id AND weixin_account_id=:weixin_account_id', array(
                                    ':group_id' => $value['id'],
                                    ':weixin_account_id' => $this->account->id,
                            )
                    );

                    if (!$group) {
                        $group = new WeixinGroup();
                        $group->setAttributes(array(
                                'group_id' => $value['id'],
                                'weixin_account_id' => $this->account->id,
                        ));
                    }

                    $group->setAttributes(array(
                            'name' => $value['name'],
                            'member_count' => $value['count'],
                    ));


                    if (!$group->save()) {
                        print_r($group->getErrors());
                        exit;
                    }
                }

                #删除不存在的群组
                $groups = WeixinGroup::model()->findAll('weixin_account_id=:weixin_account_id', array(
                        ':weixin_account_id' => $this->account->id,
                ));

                foreach ($groups as $key => $value) {
                    if (!in_array($value->group_id, $group_ids)) {
                        $value->delete();
                    }
                }


                //

                //exit($sql);
                //Yii::app()->db->createCommand($sql)->query();

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }


        //print_r($result['data']['groups']);exit;

        if (Yii::app()->request->isAjaxRequest) {
            $this->jsonout($result);
        }
    }
}
