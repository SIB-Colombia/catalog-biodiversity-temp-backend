<?php
/* @var $this CatalogoespeciesController */
/* @var $model Catalogoespecies */

$this->breadcrumbs=array(
	'Catalogoespecies'=>array('index'),
	$model->catalogoespecies_id=>array('view','id'=>$model->catalogoespecies_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Catalogoespecies', 'url'=>array('index')),
	array('label'=>'Create Catalogoespecies', 'url'=>array('create')),
	array('label'=>'View Catalogoespecies', 'url'=>array('view', 'id'=>$model->catalogoespecies_id)),
	array('label'=>'Manage Catalogoespecies', 'url'=>array('admin')),
);
?>

<h1>Update Catalogoespecies <?php echo $model->catalogoespecies_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>