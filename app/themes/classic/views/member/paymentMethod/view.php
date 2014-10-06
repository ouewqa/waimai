<?php
switch ($model->sign) {
    case 'normal':

        break;

    case 'alipay':

        break;

    case 'weixin':

        break;
}

$this->renderPartial('_' . $model->sign, array('model' => $model, 'config' => $config));