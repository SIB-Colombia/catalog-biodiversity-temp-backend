<?php
/* @var $this PctesaurosCeController */
/* @var $data PctesaurosCe */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_tesauros')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_tesauros), array('view', 'id'=>$data->id_tesauros)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('catalogoespecies_id')); ?>:</b>
	<?php echo CHtml::encode($data->catalogoespecies_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tesauronombre')); ?>:</b>
	<?php echo CHtml::encode($data->tesauronombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grupohumano')); ?>:</b>
	<?php echo CHtml::encode($data->grupohumano); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idioma')); ?>:</b>
	<?php echo CHtml::encode($data->idioma); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regionesgeograficas')); ?>:</b>
	<?php echo CHtml::encode($data->regionesgeograficas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paginaweb')); ?>:</b>
	<?php echo CHtml::encode($data->paginaweb); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('tesaurocompleto')); ?>:</b>
	<?php echo CHtml::encode($data->tesaurocompleto); ?>
	<br />

	*/ ?>

</div>