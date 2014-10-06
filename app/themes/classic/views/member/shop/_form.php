<?php
/* @var $this ShopController */
/* @var $model Shop */
/* @var $form TbActiveForm */

?>


<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=IW9wkmFI01NEvdq1kGRgZb8Z"></script>
<div class="form">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'shop-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation' => false,
    )); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 5, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldControlGroup($model, 'telephone', array('span' => 5, 'maxlength' => 255)); ?>

    <?php echo $form->textFieldControlGroup($model, 'sn', array('span' => 5)); ?>


    <?php
    $this->widget('ext.ueditor.Ueditor', array(
            'model' => $model,
            'attribute' => 'description',
    ));
    ?>


    <?php echo $form->dropDownList($model, 'province', CHtml::listData(CateArea::model()->getProvinceList(), 'name', 'name'), array(
            'class' => 'area_select vm',
            "onchange" => "",
            'empty' => '-请选择省-',
            'ajax' => array(
                    'url' => Yii::app()->createUrl('/member/public/getDynamicArea'),
                    'update' => '#Shop_city',
                    'data' => array('name' => 'js:this.value', 'level' => 2),

            ),
    )); ?>
    <?php echo $form->dropDownList($model, 'city', $model->city ? CHtml::listData(CateArea::model()->getCityList($model->province), 'name', 'name') : array(), array(
            'class' => 'area_select vm',
            'onchange' => '',
            'empty' => '-请选择市-',
            'ajax' => array(
                    'url' => Yii::app()->createUrl('/member/public/getDynamicArea'),
                    'update' => '#Shop_district',
                    'data' => array('name' => 'js:this.value', 'level' => 3),
            ),
    )); ?>
    <?php echo $form->dropDownList($model, 'district', $model->district ? CHtml::listData(CateArea::model()->getDistrictList($model->city), 'name', 'name') : array(), array('empty' => '-请选择区-')); ?>

    <?php echo $form->textFieldControlGroup($model, 'address', array('span' => 5, 'maxlength' => 255)); ?>

    <div class="control-group">
        <label class="control-label" for="Shop_address">标记地理位置</label>
        <?php
        echo TbHtml::button('打开地图', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
        )); ?>


    </div>


    <?php echo $form->hiddenField($model, 'map_point'); ?>

    <div class="control-group">
        <label class="control-label" for="Shop_opening_time_start">开始营业时间</label>

        <div class="controls">
            <?php
            $this->widget(
                    'yiiwheels.widgets.formhelpers.WhTimePickerHelper',
                    array(
                            'id' => 'Shop_opening_time_start',
                            'inputOptions' => array('class' => 'input-medium'),
                            'name' => 'Shop[opening_time_start]',
                            'value' => $model->opening_time_start ? $model->opening_time_start : '08:00'
                    )
            );
            ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="Shop_opening_time_end">结束营业时间</label>

        <div class="controls">
            <?php
            $this->widget(
                    'yiiwheels.widgets.formhelpers.WhTimePickerHelper',
                    array(
                            'id' => 'Shop_opening_time_end',
                            'inputOptions' => array('class' => 'input-medium'),
                            'name' => 'Shop[opening_time_end]',
                            'value' => $model->opening_time_end ? $model->opening_time_end : '20:00'
                    )
            );
            ?>
        </div>
    </div>


    <?php
    $this->widget('ext.ueditor.Ueditor', array(
            'model' => $model,
            'attribute' => 'delivery_explain',
    ));
    ?>


    <div class="control-group"><label class="control-label" for="Shop_minimum_charge">起送价</label>

        <div class="controls">
            <div class="input-prepend">
                <span class="add-on">¥</span>
                <?php $this->widget('yiiwheels.widgets.maskmoney.WhMaskMoney', array(
                        'name' => 'Shop[minimum_charge]',
                        'id' => 'Shop_minimum_charge',
                        'value' => $model->minimum_charge ? $model->minimum_charge : '0:00',
                ));?>
            </div>
        </div>
    </div>


    <div class="control-group"><label class="control-label" for="Shop_express_fee">送餐费</label>

        <div class="controls">
            <div class="input-prepend">
                <span class="add-on">¥</span>
                <?php $this->widget('yiiwheels.widgets.maskmoney.WhMaskMoney', array(
                        'name' => 'Shop[express_fee]',
                        'id' => 'Shop_express_fee',
                        'value' => $model->express_fee ? $model->express_fee : '0:00',
                ));?>
            </div>

        </div>
    </div>


    <div class="form-actions">
        <?php echo TbHtml::submitButton($model->isNewRecord ? '创建' : '保存', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_LARGE,
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">拖动图钉，移动到你的当前位置</h3>
    </div>
    <div class="modal-body">
        <div id="allmap"></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal">保存标记结果</button>
    </div>
</div>
<style type="text/css">
    #allmap {
        width: 530px;
        height: 400px;
        overflow: hidden;
        margin: 0;
    }
</style>

<script>
    function initMap (point) {

        var map = new BMap.Map('allmap');

        if (point) {

        }


        $('#Shop_map_point').val(point.lng + ',' + point.lat);

        map.centerAndZoom(point, 18);


        //移动到中心点
        window.setTimeout(function () {
            map.panTo(point);
        }, 1000);


        //允许缩放
        map.enableScrollWheelZoom();

        //放大缩小
        map.addControl(new BMap.NavigationControl());

        //添加默认缩略地图控件
        map.addControl(new BMap.OverviewMapControl());
        map.addControl(new BMap.OverviewMapControl({isOpen: false, anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));

        //由于有3D图，需要设置城市哦
        //map.setCurrentCity(city);


        var icon = new BMap.Icon('/images/baidu_map_pin.png', new BMap.Size(20, 32), {
            anchor: new BMap.Size(10, 30),
            infoWindowAnchor: new BMap.Size(10, 0)
        })

        var marker = new BMap.Marker(point, {
            icon: icon,
            enableDragging: true,
            raiseOnDrag: true
        });

        map.addOverlay(marker);

        marker.addEventListener('dragend', function (e) {
            $('#Shop_map_point').val(e.point.lng + ',' + e.point.lat);
            //console.log(e.point.lng + ', ' + e.point.lat);
        });

        var label = new BMap.Label("将我移到你的当前位置", {offset: new BMap.Size(20, -10)});
        marker.setLabel(label);


        /*
         */

    }

    $(document).ready(function () {

        $('#myModal').on('show', function () {
            var city = $("#Shop_city").find("option:selected").text(),
                    district = $("#Shop_district").find("option:selected").text(),
                    address = $("#Shop_address").val(),
                    map_point = $("#Shop_map_point").val(),
                    point;


            if (!city || !district || !address) {
                alert('请填写你的省、市、区及详细地址');
                return false;
            } else {

                //console.log('map_point', map_point);

                if (map_point) {
                    var map = map_point.split(",");
                    point = new BMap.Point(map[0], map[1]);
                    console.log('已有标记', point);
                    initMap(point);
                } else {
                    console.log('自动转换');
                    // 创建地址解析器实例
                    var myGeo = new BMap.Geocoder();
                    // 将地址解析结果显示在地图上,并调整地图视野
                    myGeo.getPoint(address, function (point) {
                        console.log('中心位置', point);
                        if (point) {
                            initMap(point);
                        } else {
                            alert('找不到当前位置，请更正你的地址。');
                            return false;
                        }

                    }, city);
                }


                //
            }

        });
    })
</script>