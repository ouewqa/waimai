<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $model AuthItemForm */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
        '权限管理' => '/auth',
        $this->capitalize($this->getTypeText(true)) => array('index'),
        Yii::t('AuthModule.main', 'New {type}', array('{type}' => $this->getTypeText())),
);
?>
    <h1><?php echo Yii::t('AuthModule.main', 'New {type}', array('{type}' => $this->getTypeText())); ?></h1>


<?php $form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        )
); ?>

<?php echo $form->hiddenField($model, 'type'); ?>
<?php echo $form->textFieldControlGroup($model, 'name'); ?>
<?php echo $form->textFieldControlGroup($model, 'description'); ?>
<?php echo $form->textAreaControlGroup($model, 'bizrule', array('style' => 'width:100%;height:100px;')); ?>
<?php echo $form->textAreaControlGroup($model, 'data', array('style' => 'width:400px;height:200px;')); ?>

<?php
$authitem = Authitem::model()->findAll('type=:type', array(
        ':type' => 2
));
$group = array(
        '#以下是资料数据模板'
);
foreach ($authitem as $key => $value) {
    $group[] = $value->name . ',';
}
?>
    <pre>
<?php echo implode(PHP_EOL, $group); ?>
</pre>
    <blockquote>业务逻辑规则为：return $a=$b;<br />
                资料为业务逻辑中的补充数据，一行一条，格式： A,2<br />
                即在规则中可用$data['A']取到变量2<br />
                $params 中常用的定义：role 用户的角色　total 总量<br />
                return $params['total']<$data[$params['role']];
    </blockquote>


    <div class="form-actions">
        <?php echo TbHtml::submitButton(
                Yii::t('AuthModule.main', 'Create'),
                array(
                        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                )
        ); ?>
        <?php echo TbHtml::linkButton(
                Yii::t('AuthModule.main', 'Cancel'),
                array(
                        'color' => TbHtml::BUTTON_COLOR_LINK,
                        'url' => array('index'),
                )
        ); ?>
    </div>

<?php $this->endWidget(); ?>