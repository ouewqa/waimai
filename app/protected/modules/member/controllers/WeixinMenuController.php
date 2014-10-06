<?php

class WeixinMenuController extends BackendController
{
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
        #判断是否填写接口信息
        if (!$this->account->appid || !$this->account->appsecret) {
            $this->error('设置微信菜单，需要你设置公众账号中的Appid及Appsecret！', array(
                    '/member/weixinAccount/update', 'id' => $this->account->id
            ));
        }

        $model = new WeixinMenu;
        $model->weixin_account_id = $this->account->id;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        #微信菜单约束　３个根菜单，每个二级菜单只能用７个子菜单

        /*if ($model->count('fid=:fid', array(
                    ':fid' => 0
                )) >= 3
        ) {
            $this->error('已有三个子菜单，不能再创建了。');
        }*/


        if (isset($_POST['WeixinMenu'])) {
            $model->attributes = $_POST['WeixinMenu'];
            if ($model->save()) {
                $model->makeCatepath();

                #构造网址
                $model->url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s',
                        $this->account->appid,
                        $this->createAbsoluteUrl('/weixin/api/oAuth', array('weixin_account_id' => $this->account->id,)),
                        $this->createAbsoluteUrl($model->url)
                );

                $this->redirect(array('index', 'id' => $model->id));
            }

        }

        $this->render('create', array(
                'model' => $model,
        ));
    }

    /**
     * 排序
     */
    public function actionSort()
    {
        if (Yii::app()->request->isAjaxRequest && $_POST['content']) {

            $sql = $menuLevel = array();
            if (isset($_POST['content']) && $_POST['content']) {
                foreach ($_POST['content'] as $key => $value) {
                    $menuLevel[$value['id']] = array();
                    $sql[] = "UPDATE weixin_menu SET fid=0, ob='{$key}', path=',{$value['id']}' WHERE id='{$value['id']}';";
                    if (isset($value['children']) && $value['children']) {
                        foreach ($value['children'] as $k => $v) {
                            $menuLevel[$value['id']][] = $k;
                            $sql[] = "UPDATE weixin_menu SET fid='{$value['id']}', ob='{$k}', path=',{$value['id']},{$v['id']}' WHERE id='{$v['id']}';";
                        }
                    }
                }
            }

            $result = array();
            #规则验证，一级不得超过三个，二级不得超过五个 todo 未将状态为假的情况过滤。
            /*if (count($menuLevel) > 3) {
                $result = array(
                        'status' => false,
                        'msg' => '更新出错',
                        'data' => '一级菜单数目最多只有3个',
                );
            } else {
                foreach ($menuLevel as $key => $value) {
                    if (count($value) > 5) {
                        $result = array(
                                'status' => false,
                                'msg' => '更新出错',
                                'data' => '二级菜单数目最多只有5个',
                        );
                        break;
                    }
                }
            }*/

            if (!$result) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $sql = implode('', $sql);
                    $rows = Yii::app()->db->createCommand($sql)->execute();
                    $transaction->commit();
                    $result = array(
                            'status' => true,
                            'msg' => '更新成功',
                            'data' => $rows,
                    );
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $result = array(
                            'status' => false,
                            'msg' => '更新出错',
                            'data' => $e->getMessage(),
                    );
                }
            }


            $this->jsonout($result);
        }
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

        if (isset($_POST['WeixinMenu'])) {
            $model->attributes = $_POST['WeixinMenu'];


            #构造网址
            $model->url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s',
                    $this->account->appid,
                    $this->createAbsoluteUrl('/weixin/api/oAuth', array('weixin_account_id' => $this->account->id,)),
                    $this->createAbsoluteUrl($model->url)
            );


            if ($model->save()) {

                $model->makeCatepath();

                $result = array(
                        'status' => true,
                        'msg' => '修改成功',

                );
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '修改失败',
                        'data' => $model->getErrors(),

                );
            }

            if (!isset($_POST['ajax'])) {
                $this->redirect(array('index', 'id' => $model->id));
            } else {
                $this->jsonout($result);
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
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        if (!$model->fid) {
            WeixinMenu::model()->deleteAll('fid=:fid', array(
                    'fid' => $model->id,
            ));
        }

        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('WeixinMenu', array(
                'criteria' => array(
                        'condition' => 'weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':weixin_account_id' => $this->account->id,
                        ),

                ),
                'sort' => array(
                        'defaultOrder' => 't.ob ASC',
                ),
                'pagination' => array(
                        'pageSize' => 100,
                ),
        ));

        $model = WeixinMenu::model()->findAll(array(
                'condition' => 'weixin_account_id=:weixin_account_id',
                'params' => array(
                        ':weixin_account_id' => $this->account->id,
                ),
                'order' => 't.ob ASC',
                'limit' => 100,
        ));
        $menu = array();
        foreach ($model as $key => $value) {
            if ($value->fid == 0) {
                $menu[$value->id] = array(
                        'id' => $value->id,
                        'name' => $value->name,
                        'status' => $value->status,
                        'children' => array(),
                );
            }
        }

        foreach ($model as $key => $value) {
            if ($value->fid != 0) {
                $menu[$value->fid]['children'][] = array(
                        'id' => $value->id,
                        'name' => $value->name,
                        'status' => $value->status,
                );
            }
        }


        $this->setPageTitle('会员消息');

        $this->render('index', array(
                'dataProvider' => $dataProvider,
                'menu' => $menu,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new WeixinMenu('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['WeixinMenu']))
            $model->attributes = $_GET['WeixinMenu'];

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WeixinMenu the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = WeixinMenu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WeixinMenu $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'weixin-menu-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSync()
    {

        $weixin = new WeixinHelper($this->account);

        $model = WeixinMenu::model()->findAll(array(
                'condition' => 'fid=:fid AND weixin_account_id=:weixin_account_id AND status=:status',
                'order' => 't.ob ASC, t.path ASC',
                'params' => array(
                        ':fid' => 0,
                        ':status' => 'Y',
                        ':weixin_account_id' => $this->account->id,
                ),
        ));


        $data = array();
        foreach ($model as $key => $value) {
            if ($value->type == 'menu') {

                $m = WeixinMenu::model()->findAll(array(
                        'condition' => 'fid=:fid AND weixin_account_id=:weixin_account_id AND status=:status',
                        'order' => 't.ob ASC, t.path ASC',
                        'params' => array(
                                ':fid' => $value->id,
                                ':status' => 'Y',
                                ':weixin_account_id' => $this->account->id,
                        ),
                ));

                $sub_button = array();

                foreach ($m as $k => $v) {
                    if ($v->type == 'click') {
                        $sub_button[] = array(
                                "type" => "click",
                                "name" => $v->name,
                                "key" => $v->key,
                        );
                    } else if ($v->type == 'view') {
                        $sub_button[] = array(
                                "type" => "view",
                                "name" => $v->name,
                                "url" => $v->url,
                        );
                    }

                }


                $data['button'][] = array(
                        'name' => $value->name,
                        'sub_button' => $sub_button,
                );
            } else if ($value->type == 'click') {
                {
                    $data['button'][] = array(
                            'name' => $value->name,
                            'type' => 'click',
                            'key' => $value->key,
                    );
                }

            } else if ($value->type == 'view') {
                {
                    $data['button'][] = array(
                            'name' => $value->name,
                            'type' => 'view',
                            'url' => $value->url,
                    );
                }

            }

        }

        /*print_r($data);
        exit;*/

        $result = $weixin->createMenu($data);


        if (Yii::app()->request->isAjaxRequest) {
            $this->jsonout($result);
        }
    }

    /**
     * 禁用菜单
     */
    public function actionForbidden()
    {
        $weixin = new WeixinHelper($this->account);

        $result = $weixin->deleteMenu();

        if (Yii::app()->request->isAjaxRequest) {
            $this->jsonout($result);
        }
    }
}