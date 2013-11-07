<?php
/* @var $this CatalogoespeciesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Catalogoespecies',
);

$this->menu=array(
	array('label'=>'Create Catalogoespecies', 'url'=>array('create')),
	array('label'=>'Manage Catalogoespecies', 'url'=>array('admin')),
);
?>

<h1>Catalogoespecies</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
