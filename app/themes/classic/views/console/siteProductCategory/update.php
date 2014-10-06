<?php
/* @var $this SiteProductCategoryController */
/* @var $model SiteProductCategory */
?>

<?php
$this->breadcrumbs=array(
	'Site Product Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SiteProductCategory', 'url'=>array('index')),
	array('label'=>'Create SiteProductCategory', 'url'=>array('create')),
	array('label'=>'View SiteProductCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SiteProductCategory', 'url'=>array('admin')),
);
?>

    <h1>Update SiteProductCategory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>