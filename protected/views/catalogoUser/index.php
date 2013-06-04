<?php
/* @var $this CatalogoUserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Catalogo Users',
);

$this->menu=array(
	array('label'=>'Create CatalogoUser', 'url'=>array('create')),
	array('label'=>'Manage CatalogoUser', 'url'=>array('admin')),
);
?>

<h1>Catalogo Users</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
