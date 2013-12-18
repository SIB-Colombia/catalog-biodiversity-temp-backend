<?php
/* @var $this CatalogoespeciesController */
/* @var $model Catalogoespecies */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'catalogoespecies-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'catalogoespecies_id'); ?>
		<?php echo $form->textField($model,'catalogoespecies_id'); ?>
		<?php echo $form->error($model,'catalogoespecies_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'citacion_id'); ?>
		<?php echo $form->textField($model,'citacion_id'); ?>
		<?php echo $form->error($model,'citacion_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contacto_id'); ?>
		<?php echo $form->textField($model,'contacto_id'); ?>
		<?php echo $form->error($model,'contacto_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fechaactualizacion'); ?>
		<?php echo $form->textField($model,'fechaactualizacion'); ?>
		<?php echo $form->error($model,'fechaactualizacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fechaelaboracion'); ?>
		<?php echo $form->textField($model,'fechaelaboracion'); ?>
		<?php echo $form->error($model,'fechaelaboracion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'titulometadato'); ?>
		<?php echo $form->textField($model,'titulometadato',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'titulometadato'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jerarquianombrescomunes'); ?>
		<?php echo $form->textArea($model,'jerarquianombrescomunes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'jerarquianombrescomunes'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->