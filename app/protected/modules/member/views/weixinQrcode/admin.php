<?php
/* @var $this WeixinQrcodeController */
/* @var $model WeixinQrcode */


$this->breadcrumbs=array(
	'Weixin Qrcodes'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List WeixinQrcode', 'url'=>array('index')),
	array('label'=>'Create WeixinQrcode', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#weixin-qrcode-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Weixin Qrcodes</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'weixin-qrcode-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'weixin_account_id',
		'scene_id',
		'ticket',
		'path',
		'type',
		/*
		'description',
		'scan_count',
		'dateline',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>