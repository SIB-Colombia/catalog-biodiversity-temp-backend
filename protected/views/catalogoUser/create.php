<?php
/* @var $this CatalogoUserController */
/* @var $model CatalogoUser */

$this->breadcrumbs=array(
	'Catalogo Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CatalogoUser', 'url'=>array('index')),
	array('label'=>'Manage CatalogoUser', 'url'=>array('admin')),
);
?>

<h1>Create CatalogoUser</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>