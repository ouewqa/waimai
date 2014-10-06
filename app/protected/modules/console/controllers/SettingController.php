<?php

class SettingController extends AdminSettingController
{

    public function actionUpdate()
    {


        if (isset($_POST) && $_POST) {

            $params = CMap::mergeArray(
                $this->getBaseParams(),
                $this->getSeoParams(),
                $this->getUcenterParams(),
                $this->getWeixinParams()
            );

            foreach ($_POST as $key => $value) {
                $model = $setting = Setting::model()->findByAttributes(
                    array(
                        'name' => $key
                    )
                );
                if (!$model) {
                    $setting = new Setting();
                    $setting->unsetAttributes();
                }

                if (array_key_exists($key, $params)) {
                    $setting->name = $key;
                    $setting->value = $value;
                    $setting->save();
                }

            }

            $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : Yii::app()->request->urlReferrer);
        }
    }


    /**
     * Lists all models.
     */
    public function actionIndex($status = 'base')
    {
        $data = array();
        //$dataProvider=new CActiveDataProvider('Setting');
        $model = Setting::model()->findAll();

        #获取数据库中的设置信息
        foreach ($model as $key => $value) {
            $data[$value['name']] = $value['value'];
        }


        $elements = array(
            'base' => $this->getBaseParams(),
            'seo' => $this->getSeoParams(),
            'ucenter' => $this->getUcenterParams(),
            'weixin' => $this->getWeixinParams(),
        );

        /*print_r($data);
        exit;*/
        foreach ($elements as $key => $value) {
            //$element[$key] =

            foreach ($value as $k => $v) {
                $e = CHtml::label(
                    $v['name'], $k
                );

                $e .= $this->makeFormDetial($data, $k, $v);

                if (isset($e)) {
                    $element[$key][] = $e;
                }
            }
        }

        $this->render('index', array(
            'element' => $element,
            'status' => $status,
        ));
    }


    /**
     * Performs the AJAX validation.
     * @param Setting $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'setting-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function makeFormDetial($data, $name, $option)
    {
        switch ($option['type']) {
            case 'select':
                $html = CHtml::dropDownList(
                    $name,
                    //$data[$name],
                    array_key_exists($name, $data) ? $data[$name] : $option['value'],
                    $option['data']
                );
                break;

            default:
                $html = CHtml::textField(
                    $name,
                    array_key_exists($name, $data) ? $data[$name] : $option['value'],
                    array(
                        'class' => 'span12'
                    )
                );

        }
        return $html;
    }

    private function getBaseParams()
    {
        return array(
            'site_name' => array(
                'name' => '网站名称',
                'type' => 'text',
            ),
            'upload_file_path' => array(
                'name' => '图片上传路径',
                'type' => 'text',
            ),
            'admin_email' => array(
                'name' => '管理员邮箱',
                'type' => 'text',
            ),
            'comment_switch' => array(
                'name' => '评论审核流程',
                'value' => 'N',
                'type' => 'select',
                'data' => array(
                    'Y' => '关闭审核',
                    'N' => '开启审核',
                )
            ),
        );
    }

    private function getSeoParams()
    {
        return array(
            'site_description' => array(
                'name' => '网站描述',
                'type' => 'text',
            ),
            'site_keyWords' => array(
                'name' => '网站关键字',
                'type' => 'text',
            ),
        );
    }

    private function getUcenterParams()
    {
        return array(
            'head_url' => array(
                'name' => 'Ucenter头像地址',
                'type' => 'text',
            ),
        );
    }

    private function getWeixinParams()
    {
        return array(
            'weixin_debug' => array(
                'name' => '微信调试开关',
                'value' => 'Y',
                'type' => 'select',
                'data' => array(
                    'Y' => '开启调试',
                    'N' => '关闭调试',
                )
            ),
            'weixin_sign' => array(
                'name' => '微信标识（验证接口身份）',
                'type' => 'text',
            ),
            'weixin_appid' => array(
                'name' => 'AppId',
                'type' => 'text',
            ),
            'weixin_appsecret' => array(
                'name' => 'AppSecret',
                'type' => 'text',
            ),

            'baidu_ak' => array(
                'name' => 'Baidu Api Key',
                'type' => 'text',
            ),

        );
    }
}
