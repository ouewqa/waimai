<?php

class WeixinMessageController extends BackendController
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


    public function actionCreate($weixin_id)
    {

        //print_r($_POST);exit;
        $user = Weixin::model()->findByPk($weixin_id);


        $model = new WeixinMessage;

        $replyList = '';

        $model->weixin_id = $weixin_id;
        $model->weixin_account_id = $this->account->id;
        $model->dateline = time();
        $model->status = 'Y';

        if (isset($_POST['WeixinMessage'])) {

            $model->attributes = $_POST['WeixinMessage'];

            #发送消息
            $weixin = new WeixinHelper($this->account);

            switch ($_POST['WeixinMessage']['type']) {
                case 'music':
                    $data = array(
                            'touser' => $user->open_id,
                            'msgtype' => "music",
                            'music' => array(
                                    'title' => $model->music_title,
                                    'description' => $model->music_description,
                                    'musicurl' => $model->music_url,
                                    'hqmusicurl' => $model->music_url,
                                    'thumb_media_id' => $weixin->uploadMedia('image', 'music.jpg'),
                            ),
                    );
                    break;

                default:
                    $data = array(
                            'touser' => $user->open_id,
                            'msgtype' => "text",
                            'text' => array(
                                    'content' => $model->message,
                            )
                    );

            }


            $result = $weixin->sendMessage($data);

            #发送成功
            if ($result['errcode'] == 0) {


                if ($model->save()) {
                    #修改消息的回复状态
                    WeixinMessage::model()->updateAll(
                            array(
                                    'status' => 'Y',
                            ),
                            'weixin_id=:weixin_id',
                            array(
                                    ':weixin_id' => $user->id,
                            )
                    );
                }

                $this->redirect(array('/member/weixinMessage/create',
                                'weixin_id' => $user->id,
                        )
                );

            } else {
                print_r($result);
                print_r($data);
                exit;
            }


//                $this->redirect(array('view', 'id' => $model->id));
        } else {

            $replyModel = WeixinMessage::model()->findTopic($user->id);
            //print_r($replyModel);
        }

        $this->render('create', array(
                'model' => $model,
                'replyModel' => $replyModel,
                'user' => $user,
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

        if (isset($_POST['WeixinMessage'])) {
            $model->attributes = $_POST['WeixinMessage'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex($status = 'N', $type = null)
    {

        $criteria = new CDbCriteria();
        $criteria->select = 't.* ';
        $criteria->condition = 't.status=:weixin_message_status AND io=:io AND t.weixin_account_id=:weixin_account_id AND t.dateline>=:dateline AND weixin.status=:weixin_status';
        $criteria->with = array('weixin');
        $criteria->params = array(
                ':weixin_message_status' => $status,
                ':io' => 'I',
                ':weixin_account_id' => $this->account->id,
                ':dateline' => $status == 'N' ? time() - (48 * 3600) : 0,
                ':weixin_status' => 'Y',
        );

        if ($type) {
            $criteria->addCondition("type='{$type}'");
        }

        $dataProvider = new CActiveDataProvider('WeixinMessage', array(
                'criteria' => $criteria,
                'sort' => array(
                        'defaultOrder' => 't.dateline DESC',
                ),
                'pagination' => array(
                        'pageSize' => 100,
                ),
        ));

        $typeData = $statusData = array();

        foreach (OutputHelper::getWeixinMessageTypeList() as $key => $value) {
            $typeData[] = array(
                    'label' => $value,
                    'active' => ($key == $type) ? true : false,
                    'url' => $this->createUrl('index', array('status' => $status, 'type' => $key)),
            );
        }

        foreach (OutputHelper::getWeixinMessageStatusList() as $key => $value) {
            $statusData[] = array(
                    'label' => $value,
                    'active' => ($key == $status) ? true : false,
                    'url' => $this->createUrl('index', array('status' => $key, 'type' => $type)),
            );
        }


        $this->render('index', array(
                'dataProvider' => $dataProvider,
                'typeData' => $typeData,
                'statusData' => $statusData,
        ));
    }


    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new WeixinMessage('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['WeixinMessage']))
            $model->attributes = $_GET['WeixinMessage'];

        $this->render('admin', array(
                'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WeixinMessage the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = WeixinMessage::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WeixinMessage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'weixin-message-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
