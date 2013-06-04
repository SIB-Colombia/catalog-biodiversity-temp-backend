<?php
$dataProvider = new CArrayDataProvider($revisores, array(
	'keyField'=>'valor',
	'sort'=>array(
		'attributes'=>array(
			'contenido', 'valor',
		),
	),
	'pagination'=>array(
		'pageSize'=>10,
		'route'=>'catalogo/update'
	),
));


$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
	//'fixedHeader' => true,
	'responsiveTable' => true,
	//'template' => "{items}",
    'id'=>'atributo-revisores-asignados-grid',
    'dataProvider'=>$dataProvider,
	'enablePagination' => true,
    'columns'=>array(
    	array( 'name'=>'Id', 'value'=>'$data["valor"]', 'htmlOptions'=>array('width'=>'80')),
    	array( 'name'=>'Nombre', 'value'=>'$data["contenido"]', 'type'=>'raw'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{delete}',
			'htmlOptions'=>array('style'=>'width: 50px'),
			'deleteButtonUrl'=>'Yii::app()->createUrl("/catalogo/delete", array("idAtributo"=>$data["valor"]))',
		),
	),
));

?>