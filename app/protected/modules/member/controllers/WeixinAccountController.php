<?php

class WeixinAccountController extends BackendController
{


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

        $count = WeixinAccount::model()->count('admin_id=:admin_id', array(
                ':admin_id' => Yii::app()->user->id,
        ));

        #判断已创建数量
        $params = array(
                'total' => $count,
        );


        #var_dump(Yii::app()->user->checkAccess($params));exit;
        if (!Yii::app()->user->checkAccess($params)) {
            $this->error("你当前已创建 {$count} 个公众账号，已不能创建，请升级你的账号");
        }


        $model = new WeixinAccount;
        $model->admin_id = Yii::app()->user->id;


        if ($count >= 10) {
            throw new CHttpException(500, '你当前只能创建一个账号');
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WeixinAccount'])) {
            $model->attributes = $_POST['WeixinAccount'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->setPageTitle('创建微信公众号');
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['WeixinAccount'])) {
            $model->attributes = $_POST['WeixinAccount'];
            if ($model->save()) {
                if ($model->default == 'Y') {
                    WeixinAccount::model()->updateAll(array(
                            'default' => 'N'
                    ), 'id!=:id AND admin_id=:admin_id', array(
                            ':id' => $model->id,
                            ':admin_id' => Yii::app()->user->id,
                    ));
                }

                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('更新公众号信息');

        $this->render('update', array(
                'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('WeixinAccount', array(
                'criteria' => array(
                        'condition' => 'admin_id=:admin_id',
                        'params' => array(
                                ':admin_id' => Yii::app()->user->id,
                        ),
                ),
                'sort' => array(
                        'defaultOrder' => 't.id DESC',
                ),
                'pagination' => array(
                        'pageSize' => 50,
                ),
        ));

        $this->setPageTitle('公众号列表');


        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new WeixinAccount('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['WeixinAccount'])) {
            $model->attributes = $_GET['WeixinAccount'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WeixinAccount the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = WeixinAccount::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WeixinAccount $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'weixin-account-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionChangeAccount($id)
    {
        $model = WeixinAccount::model()->findByPk($id);
        $model->default = 'Y';

        if ($model->save()) {
            WeixinAccount::model()->updateAll(
                    array(
                            'default' => 'N',
                    ),
                    'id!=:id AND `default`=:default AND admin_id=:admin_id',
                    array(
                            ':id' => $model->id,
                            ':default' => 'Y',
                            ':admin_id' => Yii::app()->user->id,
                    )
            );
            $this->redirect(Yii::app()->request->urlReferrer);
        } else {
            print_r($model->getErrors());
        }
    }

    public function actionWeixinMenu()
    {

        $this->setPageTitle('设置微信菜单');
        $this->render('weixinMenu');

    }
}