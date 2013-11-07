<?php
/* @var $this PctesaurosCeController */
/* @var $model PctesaurosCe */

$this->breadcrumbs=array(
	'Pctesauros Ces'=>array('index'),
	$model->id_tesauros=>array('view','id'=>$model->id_tesauros),
	'Update',
);

$this->menu=array(
	array('label'=>'List PctesaurosCe', 'url'=>array('index')),
	array('label'=>'Create PctesaurosCe', 'url'=>array('create')),
	array('label'=>'View PctesaurosCe', 'url'=>array('view', 'id'=>$model->id_tesauros)),
	array('label'=>'Manage PctesaurosCe', 'url'=>array('admin')),
);
?>

<h1>Update PctesaurosCe <?php echo $model->id_tesauros; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>