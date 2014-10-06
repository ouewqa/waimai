<?php

class WeixinController extends BackendController
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
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Weixin'])) {
            $model->attributes = $_POST['Weixin'];


            if ($model->save()) {

                #同步用户组
                if ($this->account->advanced_interface == 'Y') {
                    $weixin = new WeixinHelper($this->account);
                    $result = $weixin->moveGroup($model->open_id, $model->weixin_group_id);
                    #有内容获取到
                    if ($result['errcode'] !== 0) {
                        /*print_r($result);
                        exit;*/
                    }
                }
                $this->redirect(array('index'));
            }

        }

        $this->setPageTitle('更新信息');

        $this->render('update', array(
                'model' => $model,
        ));
    }


    /**
     * Lists all models.
     */
    public function actionIndex($status = 'Y')
    {
        $model = new Weixin();

        $criteria = new CDbCriteria;
        $criteria->together = true;
        //$criteria->with = array('weixinGroup');
        $criteria->condition = 't.status=:status AND t.weixin_account_id=:weixin_account_id';
        $criteria->params = array(
                ':status' => $status,
                ':weixin_account_id' => $this->account->id,
        );

        if (isset($_GET['Weixin'])) {

            if (isset($_GET['Weixin']['uid']) && $_GET['Weixin']['uid']) {
                $criteria->compare('t.uid', $_GET['Weixin']['uid'], false);
                $model->uid = $_GET['Weixin']['uid'];
            }

            if (isset($_GET['Weixin']['username']) && $_GET['Weixin']['username']) {
                $criteria->compare('t.username', $_GET['Weixin']['username'], false);
                $model->uid = $_GET['Weixin']['username'];
            }

            if (isset($_GET['Weixin']['id']) && $_GET['Weixin']['id']) {
                $criteria->compare('t.id', $_GET['Weixin']['id'], false);
                $model->id = $_GET['Weixin']['id'];
            }

            if (isset($_GET['Weixin']['nickname']) && $_GET['Weixin']['nickname']) {
                $criteria->compare('nickname', $_GET['Weixin']['nickname'], true);
                $model->nickname = $_GET['Weixin']['nickname'];
            }

            if (isset($_GET['Weixin']['province']) && $_GET['Weixin']['province']) {
                $criteria->compare('province', $_GET['Weixin']['province'], true);
                $model->province = $_GET['Weixin']['province'];
            }

            if (isset($_GET['Weixin']['city']) && $_GET['Weixin']['city']) {
                $criteria->compare('city', $_GET['Weixin']['city'], true);
                $model->city = $_GET['Weixin']['city'];
            }

            if (isset($_GET['Weixin']['realname']) && $_GET['Weixin']['realname']) {
                $criteria->compare('realname', $_GET['Weixin']['realname'], true);
                $model->realname = $_GET['Weixin']['realname'];
            }

            if (isset($_GET['Weixin']['qq']) && $_GET['Weixin']['qq']) {
                $criteria->compare('qq', $_GET['Weixin']['qq'], true);
                $model->qq = $_GET['Weixin']['qq'];
            }

            if (isset($_GET['Weixin']['email']) && $_GET['Weixin']['email']) {
                $criteria->compare('email', $_GET['Weixin']['email'], true);
                $model->email = $_GET['Weixin']['email'];
            }


            if (isset($_GET['Weixin']['jp_level']) && $_GET['Weixin']['jp_level']) {
                $criteria->compare('jp_level', $_GET['Weixin']['jp_level']);
                $model->jp_level = $_GET['Weixin']['jp_level'];
            }


            if (isset($_GET['Weixin']['identity']) && $_GET['Weixin']['identity']) {
                $criteria->compare('identity', $_GET['Weixin']['identity']);
                $model->jp_level = $_GET['Weixin']['identity'];
            }


            if (isset($_GET['Weixin']['sex']) && $_GET['Weixin']['sex']) {
                $criteria->compare('sex', $_GET['Weixin']['sex']);
                $model->sex = $_GET['Weixin']['sex'];
            }

            if (isset($_GET['Weixin']['mobile'])) {
                $criteria->compare('mobile', $_GET['Weixin']['mobile']);
                $model->mobile = $_GET['Weixin']['mobile'];
            }

            if (isset($_GET['Weixin']['weixin_group_id']) && $_GET['Weixin']['weixin_group_id']) {
                $criteria->compare('weixin_group_id', $_GET['Weixin']['weixin_group_id']);
                $model->weixin_group_id = $_GET['Weixin']['weixin_group_id'];
            }


        }

        $dependecy = new CDbCacheDependency('SELECT MAX(id) FROM `weixin` WHERE weixin_account_id=' . $this->account->id);
        $dataProvider = new CActiveDataProvider(Weixin::model()->cache(86400, $dependecy, 2), array(
                'criteria' => $criteria,
                'sort' => array(
                        'defaultOrder' => $status == 'Y' ? 't.dateline DESC' : 't.updatetime DESC',
                ),
                'pagination' => array(
                        'pageSize' => 50,
                ),
        ));

        $data = array();
        foreach (OutputHelper::getWeixinSubcribeStatusList() as $key => $value) {
            $data[] = array(
                    'label' => $value,
                    'active' => ($key == $status) ? true : false,
                    'url' => $this->createUrl('index', array('status' => $key)),
            );
        }

        $this->setPageTitle('微信会员');

        $this->render('index', array(
                'status' => $status,
                'dataProvider' => $dataProvider,
                'data' => $data,
                'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Weixin('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Weixin']))
            $model->attributes = $_GET['Weixin'];

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Weixin the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Weixin::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Weixin $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'weixin-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 会员分布情况
     */
    public function actionDistribution()
    {
        $baidu_ak = $this->account->baidu_ak;
        $data = WeixinLocation::model()->findLatestLocation($this->account->id, 300);

        $this->render('distribution', array(
                'baidu_ak' => $baidu_ak,
                'data' => CJSON::encode($data),
        ));

    }

    /**
     * 同步会员数据
     */
    public function actionSync()
    {

        $weixin = new WeixinHelper($this->account);
        $result = $weixin->getSubscribers();

        //print_r($result);exit;
        #有内容获取到
        if ($result && $result['errcode'] == 0) {

            $subscriber = $result['data']['data']['openid'];

            if ($result['data']['total'] >= 10000) {
                $result = $weixin->getSubscriber($result['data']['next_openid']);
                $subscriber = CMap::mergeArray($subscriber, $result['data']['data']['openid']);
            }


            #查找未更新的会员数据
            if ($subscriber) {

                $model = Weixin::model()->findAllSubscriber($this->account->id);

                //exit('123');
                #subscribedUser
                $subscribedUser = array();

                foreach ($model as $key => $value) {
                    $subscribedUser[] = $value->open_id;
                }

                $subscriber = array_diff($subscriber, $subscribedUser);
            }

            $sql = '';
            foreach ($subscriber as $key => $value) {
                #构造SQL
                $dateline = time();
                $sql .= 'INSERT INTO ' . Weixin::model()->tableName() .
                        "(`weixin_account_id`, `open_id`, `source`, `status`, `dateline`) VALUE('{$this->account->id}','{$value}', '{$this->account->source}', 'Y', '{$dateline}');";

            }

            exit($sql);
            if ($sql) {
                #插入数据库
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    Yii::app()->db->createCommand($sql)->queryAll();
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        } else {
            var_dump($result);
            exit;
        }


        if (Yii::app()->request->isAjaxRequest) {
            $this->jsonout(array(
                    'status' => true,
                    'msg' => '操作成功',
                    'data' => array(
                            'new' => count($subscriber),
                    )
            ));
        }
    }

    public function actionSyncUserInfo($limit = 20, $offset = 0)
    {
        Yii::import('application.modules.weixin.helpers.WeixinHelper', true);
        $weixin = new WeixinHelper($this->account);

        $user = Weixin::model()->findAll(array(
                'condition' => 'status=:status',
                'params' => array(':status' => 'Y'),
                'order' => 'id DESC',
                'limit' => $limit,
                'offset' => $offset,
        ));

        if ($user) {
            foreach ($user as $key => $value) {
                $result = $weixin->getSubscriberInfo($value->open_id);


                if ($result['errcode'] === 0) {

                    //print_r($result['data']);
                    $oldData = $value->getAttributes();

                    try {
                        if (!$oldData['nickname'] || $oldData['nickname'] == '无名大侠') {
                            $value->attributes = CMap::mergeArray($oldData, array(
                                    'nickname' => OutputHelper::strcut($result['data']['nickname'], 20),
                                    'sex' => $result['data']['sex'],
                                    'language' => $result['data']['language'],
                                    'city' => $result['data']['city'],
                                    'province' => $result['data']['province'],
                                    'country' => $result['data']['country'],
                                    'headimgurl' => $result['data']['headimgurl'],
                                    'dateline' => $result['data']['subscribe_time'],
                                    'status' => $result['data']['subscribe'] == 0 ? 'N' : 'Y',
                                    'last_update_time' => time(),
                            ));
                        } else {
                            $value->attributes = CMap::mergeArray($oldData, array(
                                    'sex' => $result['data']['sex'],
                                    'language' => $result['data']['language'],
                                    'city' => $result['data']['city'],
                                    'province' => $result['data']['province'],
                                    'country' => $result['data']['country'],
                                    'headimgurl' => $result['data']['headimgurl'],
                                    'dateline' => $result['data']['subscribe_time'],
                                    'status' => $result['data']['subscribe'] == 0 ? 'N' : 'Y',
                                    'last_update_time' => time(),
                            ));
                        }


                        //print_r($user->attributes);exit;

                        if (!$value->save()) {
                            var_dump($value->attributes);
                            var_dump($value->getErrors());
                            exit;
                        }

                        echo '#' . ++$key . ' ' . $value->nickname, '｜', $value->id, '<br />';

                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        exit;
                    }

                }
            }

            $this->success('更新成功', array('syncUserInfo', 'offset' => $offset + $limit, 'limit' => $limit), 0);

        } else {
            $this->success('更新完毕', array('/console/weixin'), 0);
        }

    }

    /**
     * 指令使用频率
     */
    public
    function actionCommandLog($scope = 'day')
    {

        $to_time = time();

        $from_times = array(
                'day' => 1,
                'yestoday' => 2,
                'week' => 7,
                '2week' => 14,
                'month' => 31,
                'season' => 90,
                'year' => 365,
        );

        if (!array_key_exists($scope, $from_times)) {
            $scope = 'day';
        }

        if ($scope == 'day') {
            $from_time = strtotime(date('Ymd'));
        } else if ($scope == 'yestoday') {
            $from_time = strtotime(date('Ymd') - 1);
            $to_time = strtotime(date('Ymd'));
        } else {
            $from_time = $to_time - 3600 * 24 * $from_times[$scope];
        }


        $dataProvider = new CActiveDataProvider('WeixinCommandLog', array(
                'criteria' => array(
                        'select' => 't.*, count(t.id) as id',
                        'condition' => 't.dateline BETWEEN :from_time AND :to_time AND weixinCommand.weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':from_time' => $from_time,
                                ':to_time' => $to_time,
                                ':weixin_account_id' => $this->account->id,
                        ),
                        'group' => 't.weixin_command_id',
                        'together' => true,
                        'with' => 'weixinCommand',
                        'order' => 'id DESC',
                ),
                'sort' => array(
                        'defaultOrder' => 't.id ASC',
                ),
                'pagination' => false,

            /*array(
                    'pageSize' => 100,
            ),*/
        ));

        if (Yii::app()->request->isAjaxRequest) {
            $data = $dataProvider->getData();

            foreach ($data as $key => $value) {
                //print_r($value->getAttributes());
                WeixinCommand::model()->updateByPk(
                        $value->weixin_command_id,
                        array(
                                'ob' => $value->id,
                        )
                );
            }

            $result = array(
                    'status' => '',
                    'errmsg' => '更新成功',
            );
            $this->jsonout($result);

        } else {
            $data = array();
            foreach (OutputHelper::getWeixinCommandLogScopeList() as $key => $value) {
                $data[] = array(
                        'label' => $value,
                        'active' => ($key == $scope) ? true : false,
                        'url' => $this->createUrl('commandLog', array('scope' => $key)),
                );
            }


            $this->render('commandLog', array(
                    'dataProvider' => $dataProvider,
                    'data' => $data,
                    'scope' => $scope,
            ));
        }


    }

    public function actionRedPacket()
    {
        Weixin::model()->sendRedPacket();

        $this->redirect(array('index'));
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
        $criteria->select = 'COUNT(id), FROM_UNIXTIME(dateline, "%Y-%m-%d") AS dateline';
        $criteria->group = 'FROM_UNIXTIME(`dateline`, "%Y-%m-%d")';
        $criteria->order = 'dateline ASC';
        $criteria->condition = 'weixin_account_id=:weixin_account_id';
        $criteria->params = array(
                ':weixin_account_id' => $this->account->id,
        );

        $criteria->addBetweenCondition('dateline', $scopes['begin'], $scopes['end']);

        $weixin = Weixin::model()->findAll($criteria);

        $subscribeData = array(
                'day' => array(),
                'people' => array(),
        );


        foreach ($weixin as $key => $value) {
            $subscribeData['day'][] = $value->dateline;
            $subscribeData['people'][] = intval($value->id);
        }


        $this->render('analyze', array(
                'scope' => $scope,
                'subscribeData' => $subscribeData,
        ));
    }
}
