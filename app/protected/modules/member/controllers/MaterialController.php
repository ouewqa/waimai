<?php

class MaterialController extends BackendController
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
    public function actionCreate($type = 'N', $keyword = null)
    {
        if ($keyword) {
            $model = Material::model()->find(
                    'weixin_account_id=:weixin_account_id AND keyword=:keyword', array(
                            ':weixin_account_id' => $this->account->id,
                            ':keyword' => $keyword,
                    )
            );
            if (!$model) {
                $model = new Material;
                $model->weixin_account_id = $this->account->id;
                $model->keyword = $keyword;
                $model->type = $type;
            }
        } else {
            $model = new Material;
            $model->weixin_account_id = $this->account->id;
            $model->type = $type;
        }


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Material'])) {
            $model->attributes = $_POST['Material'];

            if (!isset($_POST['ajax'])) {
                $model->image = AttachmentHelper::upload($model, 'image');
            }

            if ($model->save()) {

                if (in_array($this->getAction()->getId(), array(
                        'followResponse', 'defaultResponse', 'lbsResponse'
                ))
                ) {
                    $this->redirect(array($this->getAction()->getId()));
                } else {
                    $this->redirect(array('index'));
                }

            }
        }

        $this->setPageTitle('创建自定义回复');


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

        if (isset($_POST['Material'])) {
            $model->attributes = $_POST['Material'];

            if (!isset($_POST['ajax'])) {
                $model->image = AttachmentHelper::upload($model, 'image');
            }


            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('更新自定义回复');


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

        $dataProvider = new CActiveDataProvider('Material', array(
                'criteria' => array(
                        'condition' => 'weixin_account_id=:weixin_account_id AND keyword NOT IN("followResponse", "defaultResponse", "lbsResponse")',
                        'params' => array(
                                ':weixin_account_id' => $this->account->id,
                        )
                ),
                'sort' => array(
                        'defaultOrder' => 't.id ASC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));

        $this->setPageTitle('自定义回复');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Material('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Material'])) {
            $model->attributes = $_GET['Material'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Material the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Material::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Material $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'material-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionFollowResponse()
    {
        $this->setPageTitle('自动关注回复');
        $this->actionCreate('N', 'followResponse');

    }

    public function actionLbsResponse()
    {
        $this->setPageTitle('地理信息回复');
        $this->actionCreate('N', 'lbsResponse');

    }

    public function actionDefaultResponse()
    {
        $this->setPageTitle('默认回复');
        $this->actionCreate('N', 'defaultResponse');

    }
}