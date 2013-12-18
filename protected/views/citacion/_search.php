<?php
/* @var $this CitacionController */
/* @var $model Citacion */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'citacion_id'); ?>
		<?php echo $form->textField($model,'citacion_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'citaciontipo_id'); ?>
		<?php echo $form->textField($model,'citaciontipo_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sistemaclasificacion_ind'); ?>
		<?php echo $form->checkBox($model,'sistemaclasificacion_ind'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'documento_titulo'); ?>
		<?php echo $form->textField($model,'documento_titulo',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'autor'); ?>
		<?php echo $form->textField($model,'autor',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editor'); ?>
		<?php echo $form->textField($model,'editor',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'publicador'); ?>
		<?php echo $form->textField($model,'publicador',array('size'=>60,'maxlength'=>190)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'editorial'); ?>
		<?php echo $form->textField($model,'editorial',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lugar_publicacion'); ?>
		<?php echo $form->textField($model,'lugar_publicacion',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'edicion_version'); ?>
		<?php echo $form->textField($model,'edicion_version',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'volumen'); ?>
		<?php echo $form->textField($model,'volumen',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'serie'); ?>
		<?php echo $form->textField($model,'serie',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'numero'); ?>
		<?php echo $form->textField($model,'numero',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'paginas'); ?>
		<?php echo $form->textField($model,'paginas',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hipervinculo'); ?>
		<?php echo $form->textField($model,'hipervinculo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_actualizacion'); ?>
		<?php echo $form->textField($model,'fecha_actualizacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_consulta'); ?>
		<?php echo $form->textField($model,'fecha_consulta'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'citacion_superior_id'); ?>
		<?php echo $form->textField($model,'citacion_superior_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'repositorio_citacion'); ?>
		<?php echo $form->textField($model,'repositorio_citacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'otros'); ?>
		<?php echo $form->textArea($model,'otros',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->