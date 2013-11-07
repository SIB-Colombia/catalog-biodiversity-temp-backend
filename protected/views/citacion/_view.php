<?php
/* @var $this CitacionController */
/* @var $data Citacion */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('citacion_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->citacion_id), array('view', 'id'=>$data->citacion_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('citaciontipo_id')); ?>:</b>
	<?php echo CHtml::encode($data->citaciontipo_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sistemaclasificacion_ind')); ?>:</b>
	<?php echo CHtml::encode($data->sistemaclasificacion_ind); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('documento_titulo')); ?>:</b>
	<?php echo CHtml::encode($data->documento_titulo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('autor')); ?>:</b>
	<?php echo CHtml::encode($data->autor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editor')); ?>:</b>
	<?php echo CHtml::encode($data->editor); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('publicador')); ?>:</b>
	<?php echo CHtml::encode($data->publicador); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editorial')); ?>:</b>
	<?php echo CHtml::encode($data->editorial); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lugar_publicacion')); ?>:</b>
	<?php echo CHtml::encode($data->lugar_publicacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edicion_version')); ?>:</b>
	<?php echo CHtml::encode($data->edicion_version); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('volumen')); ?>:</b>
	<?php echo CHtml::encode($data->volumen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serie')); ?>:</b>
	<?php echo CHtml::encode($data->serie); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero')); ?>:</b>
	<?php echo CHtml::encode($data->numero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paginas')); ?>:</b>
	<?php echo CHtml::encode($data->paginas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hipervinculo')); ?>:</b>
	<?php echo CHtml::encode($data->hipervinculo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_actualizacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_consulta')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_consulta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('citacion_superior_id')); ?>:</b>
	<?php echo CHtml::encode($data->citacion_superior_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('repositorio_citacion')); ?>:</b>
	<?php echo CHtml::encode($data->repositorio_citacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otros')); ?>:</b>
	<?php echo CHtml::encode($data->otros); ?>
	<br />

	*/ ?>

</div>