<?php
/* @var $this ShopOrderController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '订单管理',
);

$this->menu = array(
        array('label' => 'Create ShopOrder', 'url' => array('create')),
        array('label' => 'Manage ShopOrder', 'url' => array('admin')),
);
?>

<h1>订单列表</h1>
<div class="btn-toolbar pull-left">
    <?php

    $items = array();
    foreach (OutputHelper::getShopOrderStatusList() as $key => $value) {

        $count = sprintf(' (%d)', ShopOrder::model()->countStatus($this->shop->id, $key));
        $items[] = array(
                'label' => $value . $count,
                'color' => $status == $key ? TbHtml::BUTTON_COLOR_INFO : null,
                'url' => $this->createUrl('index', array(
                                'status' => $key,
                        ))
        );
    }
    echo TbHtml::buttonGroup($items); ?>
</div>


<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'type' => 'striped bordered',
        'dataProvider' => $dataProvider,
        //'template' => "{items}",
        'columns' => array_merge(array(
                'id',
                array(
                        'name' => 'dateline',
                        'header' => '订单时间',
                        'value' => 'OutputHelper::timeFormat($data->dateline)',
                ),
                array(
                        'class' => 'yiiwheels.widgets.grid.WhRelationalColumn',
                        'name' => '订单详情',
                        'url' => $this->createUrl('view'),
                        'value' => '"订单内容"',
                        'afterAjaxUpdate' => 'js:function(tr,rowid,data){}'
                ),
                array(
                        'name' => 'realname',
                        'header' => '姓名',
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->usedAddresses->realname, array("weixin/update","id"=>$data->weixin_id), array("target"=>"_blank"))',
                ),
                array(
                        'name' => 'mobile',
                        'header' => '手机号码',
                        'value' => '$data->usedAddresses->mobile',
                ),
                array(
                        'name' => 'address',
                        'header' => '地址',
                        'value' => '$data->usedAddresses->district.$data->usedAddresses->address',
                ),
                'comment',

        ), array(

                /*array(
                        'name' => 'payment_method_id',
                        'header' => '支付方式',
                        'value' => '$data->paymentMethod->name',
                ),*/
                array(
                        'name' => 'payment_method_id',
                        'header' => '是否已支付',
                        'value' => 'OutputHelper::getPaidStatusList($data->paid)',
                ),
                array(
                        'name' => 'money',
                        'header' => '金额',
                        'value' => '$data->money',
                ),
                array(
                    /*'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{print}',
                    'buttons' => array(
                            'print' => array(
                                    'label' => '',
                                    'color' => 'info',
                                    'buttonType' => 'ajaxLink',
                                    'ajaxOptions' => array(
                                            'success' => 'js:function(data){console.log(data)}',
                                            'dataType' => 'JSON',
                                            'url' => $this->createUrl('print'),
                                    ),

                            ),
                    ),*/

                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{print}',
                        'buttons' => array
                        (
                                'print' => array
                                (
                                        'label' => '打印',
                                        'icon' => 'icon-print',
                                        'url' => 'Yii::app()->createUrl("/member/shopOrder/print", array("order_id" => $data->id))',
                                        'click' => 'js:function(){
                                        var url = $(this).attr("href");
                                        $.get(url,function(data){
                                            alert(data.msg);
                                        });
                                        return false;
                                        }'

                                ),
                        ),

                ),
        )),
)); ?>

<script>
    $(document).ready(function () {

        $("body").delegate(".btn_set_status", "click", function (event) {
            var obj = event.target;

            $.post("<?php echo $this->createUrl('setStatus');?>", {
                        "id": $(obj).attr('data-id'),
                        "status": $(obj).attr('data-status')
                    },
                    function (data) {
                        if (data.status) {
                            $('#relatedinfo' + $(obj).attr('data-id')).remove();
                        }
                        console.log(data.time); //  2pm
                    }, "json");

        });


    })
</script>
