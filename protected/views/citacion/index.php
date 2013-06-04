<?php
/* @var $this CitacionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Citacions',
);

$this->menu=array(
	array('label'=>'Create Citacion', 'url'=>array('create')),
	array('label'=>'Manage Citacion', 'url'=>array('admin')),
);
?>

<h1>Citacions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
