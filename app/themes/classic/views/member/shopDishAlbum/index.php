<?php
/* @var $this ShopDishAlbumController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
        '菜品列表' => array('shopDish/index'),
        '菜品相册',
);

$this->menu = array(
        array('label' => 'Create ShopDishAlbum', 'url' => array('create')),
        array('label' => 'Manage ShopDishAlbum', 'url' => array('admin')),
);
?>

<h1>《<?php echo $model->name; ?>》的相册</h1>

<div class="control-group">
    <?php echo TbHtml::button('上传图片', array(
            'color' => 'success',
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
    )); ?>

</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'type' => array(
                TbHtml::GRID_TYPE_STRIPED,
                TbHtml::GRID_TYPE_BORDERED,
                TbHtml::GRID_TYPE_HOVER
        ),
        //'template' => "{items}",
        'columns' => array(
                'id',
                array(
                        'name' => 'image',
                        'type' => 'raw',
                        'value' => 'CHtml::image(ImageHelper::thumb($data->image, 180, 100), "$data->description",array())',
                ),
                array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => ' {delete} ',
                        'buttons' => array(
                                'album' => array(
                                        'label' => '更多图片',
                                        'icon' => 'tags',
                                        'url' => 'Yii::app()->createUrl("/member/shopDishAlbum/index", array("shop_dish_id"=>$data->id))',

                                )
                        )
                ),
        ),
)); ?>



<div id="myModal" role="dialog" tabindex="-1" class="modal hide fade">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3>上传图片</h3></div>
    <div class="modal-body">
        <?php
        $this->widget(
                'yiiwheels.widgets.fileupload.WhFileUpload',
                array(
                        'name' => 'upfile',
                        'url' => Yii::app()->createUrl('/member/fileUpload/yiiWheels', array('action' => 'uploadimage')),
                        'multiple' => true,
                        'options' => array(
                                'dataType' => 'json',
                                'completed' => 'js:function(e, data){
                                     $.each(data.result, function(i, file){
                                        insertShopDishAlbum(file);
                                    });

                            }'

                        )
                )
        );
        ?>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn" name="yt2" type="button">完成</button>
    </div>
</div>


<script>
    function insertShopDishAlbum (file) {
        if (file.url) {
            console.log(file.url);
            $.post("<?php echo $this->createUrl('/member/shopDishAlbum/upload',
                    array(
                            'shop_dish_id' => $model->id
                    )
                    )?>",
                    { "image": file.url },
                    function (data) {
                        console.log(data);
                    },
                    "json");
        }

    }

    $(document).ready(function () {
        $('#myModal').on('hidden', function () {
            location.reload();
        })
    })
</script>

