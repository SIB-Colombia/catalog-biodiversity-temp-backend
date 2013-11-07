<?php
/* @var $this CatalogoespeciesController */
/* @var $model Catalogoespecies */

$this->breadcrumbs=array(
	'Catalogoespecies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Catalogoespecies', 'url'=>array('index')),
	array('label'=>'Manage Catalogoespecies', 'url'=>array('admin')),
);
?>

<h1>Create Catalogoespecies</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>