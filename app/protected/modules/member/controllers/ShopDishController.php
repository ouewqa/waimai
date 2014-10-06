<?php

class ShopDishController extends BackendController
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

        $count = ShopDish::model()->count('weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => $this->account->id,
        ));

        #判断已创建数量
        $params = array(
                'total' => $count,
        );
        if (!Yii::app()->user->checkAccess($params)) {
            $this->error("你当前已创建 {$count} 个菜品，已不能创建，请升级你的账号");
        }


        #判断是否已添加分类
        $category = ShopDishCategory::model()->findOwerCategory($this->account->id);
        if (count($category) <= 0) {
            $this->error('你当前还未添加分类，请先添加分类!', array(
                    'shopDishCategory/create'
            ));
        }

        $model = new ShopDish;

        $model->weixin_account_id = $this->account->id;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopDish'])) {
            $model->attributes = $_POST['ShopDish'];

            if (!isset($_POST['ajax'])) {
                $model->image = AttachmentHelper::upload($model, 'image');
            }

            if ($model->save()) {
                $this->redirect(array('index', 'status' => $model->status));
            }
        }

        $this->setPageTitle('创建菜品');
        $this->render('create', array(
                'model' => $model,
                'category' => $category,
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

        #判断是否已添加分类
        $category = ShopDishCategory::model()->findOwerCategory($this->account->id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopDish'])) {
            $model->attributes = $_POST['ShopDish'];

            if (!isset($_POST['ajax'])) {
                $model->image = AttachmentHelper::upload($model, 'image');
            }

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('更新菜品');

        $this->render('update', array(
                'model' => $model,
                'category' => $category,
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
    public function actionIndex($status = 'Y')
    {
        $dataProvider = new CActiveDataProvider('ShopDish', array(
                'criteria' => array(
                        'condition' => 't.status=:status AND t.weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':status' => $status,
                                ':weixin_account_id' => $this->account->id
                        ),
                        #'with' => 'shopDishCategory',
                        #'together' => true,
                ),
                'sort' => array(
                        'defaultOrder' => 't.ob ASC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));

        $this->setPageTitle('菜品管理');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new ShopDish('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ShopDish'])) {
            $model->attributes = $_GET['ShopDish'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShopDish the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShopDish::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShopDish $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-dish-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}