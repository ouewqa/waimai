<header class="bar bar-nav">
    <?php echo CHtml::link('<small>上一页</small>', array('product/order'), array(
            'class' => 'icon icon-left-nav pull-left',
            'data-transition' => 'slide-out',
            'data-ignore' => 'push'
    )); ?>
    <h1 class="title">添加地址</h1>
</header>

<div class="content">
    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'feedback-form',
            'htmlOptions' => array(
                    'class' => 'input-group',
            ),
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <div class="input-row">
        <label for="UsedAddresses_realname">真实姓名</label>
        <?php echo $form->textField(
                $model,
                'realname',
                array(
                        'size' => 10,
                        'maxlength' => 10,
                        'placeholder' => '真实姓名',
                        'value' => $this->weixin->realname,
                )); ?>
    </div>

    <div class="input-row">
        <label for="UsedAddresses_mobile">手机号码</label>
        <?php echo $form->textField($model,
                'mobile',
                array(
                        'size' => 11,
                        'maxlength' => 11,
                        'placeholder' => '手机号码',
                        'value' => $this->weixin->mobile,
                )); ?>
    </div>

    <div class="content-padded" id="cateArea">
        <?php echo $form->dropDownList($model,
                'province',
                CHtml::listData(CateArea::model()->getProvinceList(), 'name', 'name'),
                array(
                        'class' => 'area_select vm',
                        "onchange" => "",
                        'empty' => '省',
                        'ajax' => array(
                                'url' => Yii::app()->createUrl('/weixin/default/getDynamicArea'),
                                'update' => '#UsedAddresses_city',
                                'data' => array('name' => 'js:this.value', 'level' => 2),

                        ),
                )); ?>

        <?php echo $form->dropDownList($model,
                'city',
                $model->city ? CHtml::listData(CateArea::model()->getCityList($model->province), 'name', 'name') : array(),
                array(
                        'class' => 'area_select vm',
                        'onchange' => '',
                        'empty' => '市',
                        'ajax' => array(
                                'url' => Yii::app()->createUrl('/weixin/default/getDynamicArea'),
                                'update' => '#UsedAddresses_district',
                                'data' => array('name' => 'js:this.value', 'level' => 3),
                        ),
                )); ?>

        <?php echo $form->dropDownList($model,
                'district',
                $model->district ? CHtml::listData(CateArea::model()->getDistrictList($model->city), 'name', 'name') : array(),
                array('empty' => '区')); ?>
        <div class="clear"></div>
    </div>

    <div class="input-row">
        <label for="UsedAddresses_address">详情地址</label>
        <?php echo $form->textField($model, 'address', array('size' => 45, 'maxlength' => 45,
                'placeholder' => '详情地址'
        )); ?>

    </div>


    <div class="content-padded">
        <button class="btn btn-positive btn-block">保存地址</button>
    </div>

    <?php $this->endWidget(); ?>
</div>
