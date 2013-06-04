<?php
/* @var $this CatalogoUserController */
/* @var $model CatalogoUser */

$this->breadcrumbs=array(
	'Catalogo Users'=>array('index'),
	$model->username,
);

$this->menu=array(
	array('label'=>'List CatalogoUser', 'url'=>array('index')),
	array('label'=>'Create CatalogoUser', 'url'=>array('create')),
	array('label'=>'Update CatalogoUser', 'url'=>array('update', 'id'=>$model->username)),
	array('label'=>'Delete CatalogoUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->username),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CatalogoUser', 'url'=>array('admin')),
);
?>

<h1>View CatalogoUser #<?php echo $model->username; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'username',
		'password',
		'contacto_id',
		'role',
	),
)); ?>
