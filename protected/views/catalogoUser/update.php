<?php
/* @var $this CatalogoUserController */
/* @var $model CatalogoUser */

$this->breadcrumbs=array(
	'Catalogo Users'=>array('index'),
	$model->username=>array('view','id'=>$model->username),
	'Update',
);

$this->menu=array(
	array('label'=>'List CatalogoUser', 'url'=>array('index')),
	array('label'=>'Create CatalogoUser', 'url'=>array('create')),
	array('label'=>'View CatalogoUser', 'url'=>array('view', 'id'=>$model->username)),
	array('label'=>'Manage CatalogoUser', 'url'=>array('admin')),
);
?>

<h1>Update CatalogoUser <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>