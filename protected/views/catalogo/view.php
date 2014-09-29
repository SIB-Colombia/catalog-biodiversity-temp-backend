<?php
/* @var $this CatalogoespeciesController */
/* @var $model Catalogoespecies */
Yii::app()->theme = 'catalogo_interno';

$this->breadcrumbs=array(
	'Catalogoespecies'=>array('index'),
	$model->catalogoespecies_id,
);

$this->widget('bootstrap.widgets.TbButtonGroup', array(
		'buttons'=>array(
				array('label'=>'Listar fichas', 'icon'=>'icon-list', 'url'=>array('index')),
		),
));
?>
<script type="text/javascript">
	var ventimp;
	
	function printFrame(div)
	{
		var elemento=document.getElementById(div);
		ventimp=window.open(' ','popimpr');
		ventimp.document.write("<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap_catalogo.css' />");
		ventimp.document.write("<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css' />");
		ventimp.document.write("<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css' />");
		ventimp.document.write("<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css' />");
		ventimp.document.write("<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-box.css' />");
		ventimp.document.write(elemento.innerHTML);
		ventimp.document.close();
		window.setTimeout('printPage()',100)
	}

	function printPage(){
		ventimp.print();
		ventimp.close();		
	}
</script>

<i class="icon-print printR" onclick="printFrame('printDiv');" style="float: right;cursor: pointer;"></i>

<h1>Datos de ficha con ID No. <?php echo $model->catalogoespecies_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'catalogoespecies_id',
		//'citacion_id',
		//'contacto_id',
		'fechaactualizacion',
		'fechaelaboracion',
		'pcaatCe.taxoncompleto',
		'pcaatCe.autor',
		'pcaatCe.paginaweb'
		//'titulometadato',
		//'jerarquianombrescomunes',
	),
)); ?>
<br>
<?php 
	$atributosDatos = $model->obtenerAtributos($model->catalogoespecies_id);
	foreach ($atributosDatos as $clave => $atributo){
		$this->widget('bootstrap.widgets.TbDetailView', array(
			'data'=>$atributo,
			'attributes'=>array(
					array('label'=>$clave,'value'=>$atributo),
					
			),
		));
	}
	//print_r($model->obtenerAtributos($model->catalogoespecies_id));
?>
<br>
<?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
			'title' => 'Nombres comunes asignados a la ficha',
    		'headerIcon' => 'icon-th-list',
    		// when displaying a table, if we include bootstra-widget-table class
    		// the table will be 0-padding to the box
    		'htmlOptions' => array('class'=>'bootstrap-widget-table'),
    	));?>
<?php echo $this->renderPartial('_nombres_comunes_asignados_ficha_update_table', array('model'=>$model)); ?>
<?php $this->endWidget();?>

<?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
	'title' => 'Departamentos asignados a la ficha',
    'headerIcon' => 'icon-th-list',
    //'htmlOptions' => array('class'=>'bootstrap-widget-table'),
));?>
<?php echo $this->renderPartial('_departamentos_asignados_ficha_update_table', array('model'=>$model)); ?>
<?php $this->endWidget();?>

<?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
	'title' => 'Regiones naturales asignadas a la ficha',
	'headerIcon' => 'icon-th-list',
	//'htmlOptions' => array('class'=>'bootstrap-widget-table'),
));?>
<?php echo $this->renderPartial('_regiones_naturales_asignadas_ficha_update_table', array('model'=>$model)); ?>
<?php $this->endWidget();?>