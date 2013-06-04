<?php
/* @var $this PctesaurosCeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pctesauros Ces',
);

$this->menu=array(
	array('label'=>'Create PctesaurosCe', 'url'=>array('create')),
	array('label'=>'Manage PctesaurosCe', 'url'=>array('admin')),
);
?>

<h1>Pctesauros Ces</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
