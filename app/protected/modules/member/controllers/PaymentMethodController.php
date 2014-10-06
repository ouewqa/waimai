<?php

class PaymentMethodController extends BackendController
{


    /**
     * Displays a particular model.
     * @param $sign
     * @throws CHttpException
     * @internal param int $id the ID of the model to be displayed
     */
    public function actionView($sign)
    {
        $model = PaymentMethod::model()->find('sign=:sign', array(
                ':sign' => $sign,
        ));

        if (!$model) {
            throw new CHttpException(404, '不存在的支付方式');
        }

        $payments = PaymentConfig::model()->findAll(
                'payment_method_id=:payment_method_id AND weixin_account_id=:weixin_account_id', array(
                        ':weixin_account_id' => $this->account->id,
                        ':payment_method_id' => $model->id,
                )
        );

        $config = array();
        foreach ($payments as $payment) {
            $config[$payment->key] = $payment->value;
        }


        if (Yii::app()->request->isAjaxRequest && isset($_POST) && $_POST) {


            $result = array();
            foreach ($_POST as $key => $value) {

                $payment = PaymentConfig::model()->find('
                    weixin_account_id=:weixin_account_id AND
                    payment_method_id=:payment_method_id AND
                    `key`=:key
                    ', array(
                        ':weixin_account_id' => $this->account->id,
                        ':payment_method_id' => $model->id,
                        ':key' => $key,
                ));

                if ($payment) {
                    $payment->value = $value;
                } else {
                    $payment = new PaymentConfig();
                    $payment->setAttributes(array(
                            'weixin_account_id' => $this->account->id,
                            'payment_method_id' => $model->id,
                            'key' => $key,
                            'value' => $value,
                    ));
                }

                if ($payment->save()) {
                    $result[] = array(
                            'status' => true,
                            'msg' => '操作成功',
                    );
                } else {
                    $result[] = array(
                            'status' => false,
                            'msg' => '操作失败，请联系管理员！',
                            'data' => $payment->getErrors(),
                    );
                }


            }

            $this->jsonout($result);
            //$payment->attributes
        }

        $result = $this->renderPartial('view', array(
                'model' => $model,
                'config' => $config,
        ), true);

        $this->out($result);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new PaymentMethod;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PaymentMethod'])) {
            $model->attributes = $_POST['PaymentMethod'];
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

        if (isset($_POST['PaymentMethod'])) {
            $model->attributes = $_POST['PaymentMethod'];
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
    public function actionIndex()
    {
        $model = PaymentMethod::model()->findAll('status=:status', array(
            ':status' => 'Y',
        ));

        $this->setPageTitle('支付设置');

        $this->render('index', array(
                'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new PaymentMethod('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['PaymentMethod'])) {
            $model->attributes = $_GET['PaymentMethod'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PaymentMethod the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PaymentMethod::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PaymentMethod $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'payment-method-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}