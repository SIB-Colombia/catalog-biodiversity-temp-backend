<?php
/* @var $this CitacionController */
/* @var $model Citacion */
Yii::app()->theme = 'catalogo_interno'; ?>

<h1>Crear citación</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>