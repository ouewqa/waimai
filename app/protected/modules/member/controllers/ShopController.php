<?php

class ShopController extends BackendController
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
        $count = Shop::model()->count('weixin_account_id=:weixin_account_id', array(
                ':weixin_account_id' => $this->account->id,
        ));

        #判断已创建数量
        $params = array(
                'total' => $count,
        );
        if (!Yii::app()->user->checkAccess($params)) {
            $this->error("你当前已创建 {$count} 个店铺，已不能创建，请升级你的账号");
        }

        $model = new Shop;
        $model->weixin_account_id = $this->account->id;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Shop'])) {
            $model->attributes = $_POST['Shop'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('添加门店');

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

        if (isset($_POST['Shop'])) {
            $model->attributes = $_POST['Shop'];
            if ($model->save()) {

                if ($model->default == 'Y') {
                    Shop::model()->updateAll(array(
                            'default' => 'N'
                    ), 'id!=:id AND weixin_account_id=:weixin_account_id', array(
                            ':id' => $model->id,
                            ':weixin_account_id' => $this->account->id,
                    ));
                }


                $this->redirect(array('index'));
            }
        }

        $this->setPageTitle('更新门店信息');

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

        $dataProvider = new CActiveDataProvider('Shop', array(
                'criteria' => array(
                        'condition' => 'weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':weixin_account_id' => $this->account->id
                        )
                )
        ));

        $this->setPageTitle('门店列表');
        $this->render('index', array(
                'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Shop('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Shop'])) {
            $model->attributes = $_GET['Shop'];
        }

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Shop the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Shop::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Shop $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * 切换店铺
     * @param $id
     */
    public function actionChangeShop($id)
    {
        $model = Shop::model()->findByPk($id);
        $model->default = 'Y';

        if ($model->save()) {
            Shop::model()->updateAll(
                    array(
                            'default' => 'N',
                    ),
                    'id!=:id AND `default`=:default AND weixin_account_id=:weixin_account_id',
                    array(
                            ':id' => $model->id,
                            ':default' => 'Y',
                            ':weixin_account_id' => Yii::app()->user->weixin_account_id,
                    )
            );
            $this->redirect(Yii::app()->request->urlReferrer);
        } else {
            print_r($model->getErrors());
        }
    }


    /**
     * 设置通知方式
     */
    public function actionNotification()
    {
        $model = $this->shop;

        if (Yii::app()->request->isPostRequest && $_POST['Shop']) {
            $model->attributes = $_POST['Shop'];
            if ($model->save()) {
                $this->redirect('notification');
            } else {

            }
        }

        $this->setPageTitle('通知方式');

        $this->render('notification', array(
                'model' => $model,
        ));
    }

    public function actionCheckPrinter()
    {

        if (Yii::app()->request->isAjaxRequest) {
            $pusher = new PushHelper($this->shop);

            if ($pusher && $result = $pusher->checkStatus()) {
                $result = array(
                        'status' => true,
                        'msg' => $result->status,
                );
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '当前打印机为离线状态，请检查设备。',
                );
            }


            $this->jsonout($result);
        }
    }

    /**
     *
     */
    public function actionTest()
    {
        if (Yii::app()->request->isAjaxRequest) {

            try {
                $pusher = new PushHelper($this->shop);

                if ($pusher->test('这是一条通知。发送时间：' . date('Y-m-d H:i:s'))) {
                    $result = array(
                            'status' => true,
                            'msg' => '测试命令已发出。',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '测试失败',
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

    public function actionLanguage()
    {


        $languages = array();
        $languageDir = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'messages' . DIRECTORY_SEPARATOR . 'zh_cn';
        $languagePath = $languageDir . DIRECTORY_SEPARATOR . $this->shop->id . '.php';
        $defaultLanguagePath = $languageDir . DIRECTORY_SEPARATOR . 'default.php';

        if (Yii::app()->request->isPostRequest) {
            unset($_POST['yt0']);
            file_put_contents($languagePath, '<?php return ' . var_export($_POST, true) . '; ?>');
        }


        if (!file_exists($defaultLanguagePath)) {
            throw new CHttpException(500, '默认语言模板不存在。');
        }

        $languages = include($defaultLanguagePath);

        if (file_exists($languagePath)) {
            $languagesDiy = include($languagePath);

            foreach ($languages as $key => $value) {
                if (isset($languagesDiy[$key])) {
                    $languages[$key] = $languagesDiy[$key];
                }
            }

        }

        $this->render('language', array(
                'languages' => $languages,
        ));
    }
}