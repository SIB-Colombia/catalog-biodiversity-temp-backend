<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'pctesauros-ce-form',
    'type'=>'horizontal',
    'enableClientValidation'=>true,
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldRow($model, 'catalogoespecies_id', array('readonly'=>true)); ?>
	<?php echo $form->textFieldRow($model, 'tesauronombre', array('size'=>50,'maxlength'=>50, 'class'=>'textareaA')); ?>
	<?php echo $form->textFieldRow($model, 'idioma', array('size'=>50,'maxlength'=>50, 'class'=>'textareaA')); ?>
	<?php echo $form->textFieldRow($model, 'grupohumano', array('size'=>50,'maxlength'=>50, 'class'=>'textareaA')); ?>
	<?php echo $form->textFieldRow($model, 'regionesgeograficas', array('size'=>60,'maxlength'=>100, 'class'=>'textareaA')); ?>
	<?php //echo $form->textFieldRow($model, 'paginaweb', array('size'=>60,'maxlength'=>255, 'class'=>'textareaA')); ?>
	<?php //echo $form->textFieldRow($model, 'tesaurocompleto', array('size'=>60,'maxlength'=>255, 'class'=>'textareaA')); ?>

<?php $this->endWidget(); ?>