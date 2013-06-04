<?php
/* @var $this PctesaurosCeController */
/* @var $model PctesaurosCe */

$this->breadcrumbs=array(
	'Pctesauros Ces'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PctesaurosCe', 'url'=>array('index')),
	array('label'=>'Manage PctesaurosCe', 'url'=>array('admin')),
);
?>

<h1>Create PctesaurosCe</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>