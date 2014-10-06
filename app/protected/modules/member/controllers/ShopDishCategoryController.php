<?php

class ShopDishCategoryController extends BackendController
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

        $count = ShopDishCategory::model()->count('weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => $this->account->id,
        ));
        #判断已创建数量
        $params = array(
                'total' => $count,
        );
        if (!Yii::app()->user->checkAccess($params)) {
            $this->error("你当前已创建 {$count} 个分类，已不能创建，请升级你的账号");
        }


        $model = new ShopDishCategory;
        $model->weixin_account_id = $this->account->id;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopDishCategory'])) {
            $model->attributes = $_POST['ShopDishCategory'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('创建分类');

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

        if (isset($_POST['ShopDishCategory'])) {
            $model->attributes = $_POST['ShopDishCategory'];
            if ($model->save()) {
                if (!Yii::app()->request->isAjaxRequest) {
                    $this->redirect(array('index'));
                }

            }
        }

        $this->setPageTitle('更新分类');

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
        $dataProvider = new CActiveDataProvider('ShopDishCategory', array(
                'criteria' => array(
                        'condition' => 'weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':weixin_account_id' => $this->account->id
                        )
                ),
                'sort' => array(
                        'defaultOrder' => 't.ob ASC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));

        $this->setPageTitle('菜品分类');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new ShopDishCategory('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ShopDishCategory'])) {
            $model->attributes = $_GET['ShopDishCategory'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShopDishCategory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShopDishCategory::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShopDishCategory $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-dish-category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}