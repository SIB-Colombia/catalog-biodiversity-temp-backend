<?php
/* @var $this CatalogoespeciesController */
/* @var $model Catalogoespecies */

$this->breadcrumbs=array(
	'Catalogoespecies'=>array('index'),
	$model->catalogoespecies_id,
);

$this->menu=array(
	array('label'=>'List Catalogoespecies', 'url'=>array('index')),
	array('label'=>'Create Catalogoespecies', 'url'=>array('create')),
	array('label'=>'Update Catalogoespecies', 'url'=>array('update', 'id'=>$model->catalogoespecies_id)),
	array('label'=>'Delete Catalogoespecies', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->catalogoespecies_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Catalogoespecies', 'url'=>array('admin')),
);
?>

<h1>View Catalogoespecies #<?php echo $model->catalogoespecies_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'catalogoespecies_id',
		'citacion_id',
		'contacto_id',
		'fechaactualizacion',
		'fechaelaboracion',
		'titulometadato',
		'jerarquianombrescomunes',
	),
)); ?>
