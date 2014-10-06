<?php
$this->breadcrumbs = array(
        '验证',
);
?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
        'tabs' => array(
                array(
                        'label' => '邮箱地址验证',
                        'content' => $this->renderPartial(
                                        '_verify_email',
                                        array(
                                                'model' => $model,
                                        ),
                                        true),
                        'active' => $type == 'email' ? true : false,
                ),
               /* array(
                        'label' => '手机号码验证',
                        'content' => $this->renderPartial(
                                        '_verify_mobile',
                                        array(
                                                'model' => $model,
                                        ),
                                        true),
                        'active' => $type == 'mobile' ? true : false
                ),*/

        ),
)); ?>