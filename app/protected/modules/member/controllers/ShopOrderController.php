<?php

class ShopOrderController extends BackendController
{


    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     * @throws CHttpException
     */
    public function actionView($id)
    {

        $model = ShopOrder::model()->cache(0)->with('items')->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, '不存在这个订单');
        }

        $this->renderPartial('view', array(
                'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    /*public function actionCreate()
    {
        $model=new ShopOrder;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ShopOrder'])) {
            $model->attributes=$_POST['ShopOrder'];
            if ($model->save()) {
                $this->redirect(array('view','id'=>$model->id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }*/

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

        if (isset($_POST['ShopOrder'])) {
            $model->attributes = $_POST['ShopOrder'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->setPageTitle('更新订单');

        $this->render('update', array(
                'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    /*public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
    }*/

    /**
     * Lists all models.
     */
    public function actionIndex($status = 1)
    {
        $dataProvider = new CActiveDataProvider('ShopOrder', array(
                'criteria' => array(
                        'select' => 't.*',
                    //'with' => array('usedAddresses'),#, 'weixin', 'paymentMethod'
                        'condition' => 't.status=:status AND shop_id=:shop_id',
                        'params' => array(
                                ':status' => $status,
                                ':shop_id' => $this->shop->id
                        ),
                ),
                'sort' => array(
                        'defaultOrder' => 't.dateline DESC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 20,
                )
        ));

        $this->setPageTitle('订单列表');
        $this->render('index', array(
                'status' => $status,
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    /*public function actionAdmin()
    {
        $model = new ShopOrder('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ShopOrder'])) {
            $model->attributes = $_GET['ShopOrder'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }*/

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShopOrder the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShopOrder::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShopOrder $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAnalyze($scope = 'thisWeek')
    {
        $method = sprintf('get%sScope', strtoupper($scope));
        if (method_exists('OutputHelper', $method)) {
            $scopes = OutputHelper::$method();
        } else {
            throw new CHttpException(500, '不存在' . $scope . '的处理方法！');
        }


        $criteria = new CDbCriteria();
        $criteria->select = 'COUNT(id) AS id, SUM(money) AS money, FROM_UNIXTIME(dateline, "%Y-%m-%d") AS dateline';
        $criteria->group = 'FROM_UNIXTIME(`dateline`, "%Y-%m-%d")';
        $criteria->order = 'dateline ASC';
        $criteria->condition = 'shop_id=:shop_id AND status>=:status';
        $criteria->params = array(
                ':status' => 20,
                ':shop_id' => $this->shop->id,
        );


        $criteria->addBetweenCondition('dateline', $scopes['begin'], $scopes['end']);

        $shopOrder = ShopOrder::model()->findAll($criteria);

        #订单数据
        $orderData = array(
                'day' => array(),
                'order' => array(),
                'money' => array(),
        );


        foreach ($shopOrder as $key => $value) {
            $orderData['day'][] = $value->dateline;
            $orderData['order'][] = intval($value->id);
            $orderData['money'][] = floatval($value->money);
        }

        $this->setPageTitle('订单统计');

        $this->render('analyze', array(
                'scope' => $scope,
                'orderData' => $orderData,
        ));
    }

    public function actionSetStatus()
    {
        if (Yii::app()->request->isAjaxRequest) {

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 0;


            $model = ShopOrder::model()->findByPk($id, 'shop_id=:shop_id', array(
                    ':shop_id' => $this->shop->id
            ));

            if (!empty($model)) {
                $model->status = $status;
                if ($model->save()) {
                    $result = array(
                            'status' => true,
                            'msg' => '设置成功',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '设置失败',
                            'data' => $model->getErrors(),
                    );
                }
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '不存在该订单',
                );
            }


            $this->jsonout($result);
        }
    }

    public function actionPrint($order_id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $model = ShopOrder::model()->findByPk($order_id);
            if ($model) {

                try {
                    $pusher = new PushHelper($this->shop);
                    $printData = $pusher->buildData($model);

                    $result = $pusher->send($printData);

                    if ($result) {
                        $result = array(
                                'status' => true,
                                'msg' => '发送成功',
                        );
                    } else {
                        $result = array(
                                'status' => false,
                                'msg' => '发送失败，请检查通知设备是否正确',
                        );
                    }
                } catch (Exception $e) {
                    $result = array(
                            'status' => false,
                            'msg' => $e->getMessage(),
                    );
                }
                $this->jsonout($result);
            }
        }


    }

}