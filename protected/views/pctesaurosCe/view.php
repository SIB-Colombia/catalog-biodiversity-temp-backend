<?php
/* @var $this PctesaurosCeController */
/* @var $model PctesaurosCe */

$this->breadcrumbs=array(
	'Pctesauros Ces'=>array('index'),
	$model->id_tesauros,
);

$this->menu=array(
	array('label'=>'List PctesaurosCe', 'url'=>array('index')),
	array('label'=>'Create PctesaurosCe', 'url'=>array('create')),
	array('label'=>'Update PctesaurosCe', 'url'=>array('update', 'id'=>$model->id_tesauros)),
	array('label'=>'Delete PctesaurosCe', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_tesauros),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PctesaurosCe', 'url'=>array('admin')),
);
?>

<h1>View PctesaurosCe #<?php echo $model->id_tesauros; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_tesauros',
		'catalogoespecies_id',
		'tesauronombre',
		'grupohumano',
		'idioma',
		'regionesgeograficas',
		'paginaweb',
		'tesaurocompleto',
	),
)); ?>
