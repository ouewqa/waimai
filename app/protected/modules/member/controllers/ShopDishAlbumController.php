<?php

class ShopDishAlbumController extends BackendController
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
        $model = new ShopDishAlbum;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopDishAlbum'])) {
            $model->attributes = $_POST['ShopDishAlbum'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopDishAlbum'])) {
            $model->attributes = $_POST['ShopDishAlbum'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

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
    public function actionIndex($shop_dish_id)
    {
        $model = ShopDish::model()->findByPk($shop_dish_id);
        if (!$model) {
            throw new CHttpException(404, '不存在这个菜品');
        }

        $dataProvider = new CActiveDataProvider('ShopDishAlbum', array(
                'criteria' => array(
                        'condition' => 'shop_dish_id=:shop_dish_id',
                        'params' => array(
                                ':shop_dish_id' => $shop_dish_id
                        )
                ),
                'sort' => array(
                        'defaultOrder' => 't.id ASC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));
        $this->render('index', array(
                'model' => $model,
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new ShopDishAlbum('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ShopDishAlbum'])) {
            $model->attributes = $_GET['ShopDishAlbum'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShopDishAlbum the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShopDishAlbum::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShopDishAlbum $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-dish-album-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUpload($shop_dish_id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $image = trim($_POST['image']);
            $model = ShopDishAlbum::model()->find(
                    'shop_dish_id=:shop_dish_id AND image=:image', array(
                            ':shop_dish_id' => $shop_dish_id,
                            ':image' => $image,
                    )
            );

            if (!$model) {
                $model = new ShopDishAlbum();
                $model->setAttributes(array(
                        'shop_dish_id' => $shop_dish_id,
                        'image' => $image,
                ));

                if ($model->save()) {
                    $result = array(
                            'status' => true,
                            'msg' => '上传成功',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '上传失败',
                            'data' => serialize($model->getErrors()),
                    );
                }

                $this->jsonout($result);
            }
        }
    }
}