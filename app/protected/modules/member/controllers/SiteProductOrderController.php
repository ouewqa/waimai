<?php

class SiteProductOrderController extends BackendController
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
    public function actionCreate($id)
    {
        $product = SiteProduct::model()->findByPk($id);
        if (!$product) {
            throw new CHttpException(404, '不存在该产品，或该产品已下架！');
        }

        $model = new SiteProductOrder;
        $model->site_product_id = $id;
        $model->admin_id = $this->user->id;
        $model->payment = 'alipay';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SiteProductOrder'])) {
            $model->attributes = $_POST['SiteProductOrder'];

            if ($model->save()) {

                switch ($model->payment) {
                    case 'alipay':
                        $payment_url = AlipayHelper::getRequestURL($model->order_sn, $product->price, $product->name, $product->description);
                        #支付流程
                        echo $payment_url;
                        break;

                    case 'tenpay':
                        $payment_url = TenpayHelper::getRequestURL($model->order_sn, $product->price, $product->name, $product->description);
                        #支付流程
                        $this->redirect($payment_url);
                        break;
                }

            }
        }

        $this->setPageTitle('购买' . $product->name);

        $this->render('create', array(
                'model' => $model,
                'product' => $product,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('SiteProductOrder', array(
                'criteria' => array(
                        'condition' => 'admin_id=:admin_id',
                        'params' => array(
                                ':admin_id' => $this->user->id
                        ),
                ),
                'sort' => array(
                        'defaultOrder' => 't.dateline DESC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 30,
                )
        ));

        $this->setPageTitle('产品订单');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SiteProductOrder the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = SiteProductOrder::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SiteProductOrder $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'site-product-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionRepay($id)
    {

        $model = $this->loadModel($id);


        switch ($model->payment) {
            case 'alipay':
                $payment_url = AlipayHelper::getRequestURL($model->order_sn, $model->siteProduct->price, $model->siteProduct->name, $model->siteProduct->description);
                #支付流程
                echo $payment_url;
                break;

            case 'tenpay':
                $payment_url = TenpayHelper::getRequestURL($model->order_sn, $model->siteProduct->price, $model->siteProduct->name, $model->siteProduct->description);
                #支付流程
                $this->redirect($payment_url);
                break;
        }
    }
}