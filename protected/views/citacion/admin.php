<?php
/* @var $this CitacionController */
/* @var $model Citacion */

$this->breadcrumbs=array(
	'Citacions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Citacion', 'url'=>array('index')),
	array('label'=>'Create Citacion', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#citacion-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Citacions</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'citacion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'citacion_id',
		'citaciontipo_id',
		'sistemaclasificacion_ind',
		'fecha',
		'documento_titulo',
		'autor',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
