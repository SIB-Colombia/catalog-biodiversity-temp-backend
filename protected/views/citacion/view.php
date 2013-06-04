<?php
/* @var $this CitacionController */
/* @var $model Citacion */
Yii::app()->theme = 'catalogo_interno';

$this->menu=array(
	array('label'=>'List Citacion', 'url'=>array('index')),
	array('label'=>'Create Citacion', 'url'=>array('create')),
	array('label'=>'Update Citacion', 'url'=>array('update', 'id'=>$model->citacion_id)),
	array('label'=>'Delete Citacion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->citacion_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Citacion', 'url'=>array('admin')),
);
?>

<h1>View Citacion #<?php echo $model->citacion_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'citacion_id',
		'citaciontipo_id',
		'sistemaclasificacion_ind',
		'fecha',
		'documento_titulo',
		'autor',
		'editor',
		'publicador',
		'editorial',
		'lugar_publicacion',
		'edicion_version',
		'volumen',
		'serie',
		'numero',
		'paginas',
		'hipervinculo',
		'fecha_actualizacion',
		'fecha_consulta',
		'citacion_superior_id',
		'repositorio_citacion',
		'otros',
	),
)); ?>
