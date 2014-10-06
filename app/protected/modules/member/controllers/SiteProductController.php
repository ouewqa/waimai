<?php

class SiteProductController extends BackendController
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->setPageTitle($model->name);

        $this->render('view', array(
                'model' => $model,
        ));
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('SiteProduct', array(
                'criteria' => array(
                        'condition' => 't.status=:status',
                        'params' => array(
                                ':status' => 'Y',
                        ),
                ),
                'sort' => array(
                        'defaultOrder' => 't.dateline DESC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));

        $this->setPageTitle('增值产品');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SiteProduct the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = SiteProduct::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SiteProduct $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'site-product-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}