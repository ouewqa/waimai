<?php

class FeedbackController extends FrontController
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
        $model = new Feedback;
        $model->weixin_id = $this->weixin->id;;
        $model->shop_id = $this->shop->id;;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Feedback'])) {
            $model->attributes = $_POST['Feedback'];
            if ($model->save()) {

                if ($model->weixin_account && !$this->weixin->weixin_account) {
                    $this->weixin->weixin_account = $model->weixin_account;
                }

                if ($model->mobile && !$this->weixin->mobile) {
                    $this->weixin->mobile = $model->mobile;
                }

                $this->weixin->save();

                #推送通知
                try {
                    $pushData = sprintf('<CB>意见反馈</CB><BR>时间：%s<BR>编号：%d<BR>微信号：%s<BR>手机：%d<BR>内容：<BR>%s', date('Y-m-d H:i:s'), $model->id, $model->weixin_account, $model->mobile, $model->content);
                    $pusher = new PushHelper($this->shop);
                    $pusher->diy($pushData);
                } catch (Exception $e) {

                }


                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
                'model' => $model,
        ));
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'reply_time DESC, dateline DESC';
        $criteria->condition = 't.weixin_id=:weixin_id';
        $criteria->params = array(
                ':weixin_id' => $this->weixin->id,
        );


        $count = Feedback::model()->count($criteria);

        $pages = new CPagination($count);

        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $feedbacks = Feedback::model()->findAll($criteria);


        $this->setPageTitle('我的留言');
        $this->render('index', array(
                'feedbacks' => $feedbacks,
                'pages' => $pages,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Feedback the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Feedback::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Feedback $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'feedback-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}