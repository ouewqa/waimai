<?php
/* @var $this WeixinAccountController */
/* @var $model WeixinAccount */
?>

<?php
$this->breadcrumbs = array(
        '微信公众账号' => array('index'),
        $model->name,
);
?>

<h1>微信公众账号使用方法</h1>

<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>步骤</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>进入微信公众平台，点击进入：<?php echo CHtml::link('https://mp.weixin.qq.com', 'https://mp.weixin.qq.com'); ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>进入开发者中心</td>
    </tr>
    <tr>
        <td>3</td>
        <td>填写服务器配置</td>
    </tr>
    <tr>
        <td>4</td>
        <td>
            URL：<span class="label label-info"><?php echo $this->createAbsoluteUrl('/weixin/api/callBack/', array('id' => $model->id, 'token' => $model->token)); ?></span>
        </td>
    </tr>
    <tr>
        <td>5</td>
        <td>Token：<span class="label label-info"><?php echo $model->token; ?></span></td>
    </tr>
    <tr>
        <td>6</td>
        <td>点击提交</td>
    </tr>
    <tr>
        <td>7</td>
        <td>启用服务器配置</td>
    </tr>
    </tbody>
</table>