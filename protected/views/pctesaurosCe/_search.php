<?php
/* @var $this PctesaurosCeController */
/* @var $model PctesaurosCe */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_tesauros'); ?>
		<?php echo $form->textField($model,'id_tesauros'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'catalogoespecies_id'); ?>
		<?php echo $form->textField($model,'catalogoespecies_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tesauronombre'); ?>
		<?php echo $form->textField($model,'tesauronombre',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grupohumano'); ?>
		<?php echo $form->textField($model,'grupohumano',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idioma'); ?>
		<?php echo $form->textField($model,'idioma',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'regionesgeograficas'); ?>
		<?php echo $form->textField($model,'regionesgeograficas',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'paginaweb'); ?>
		<?php echo $form->textField($model,'paginaweb',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tesaurocompleto'); ?>
		<?php echo $form->textField($model,'tesaurocompleto',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->