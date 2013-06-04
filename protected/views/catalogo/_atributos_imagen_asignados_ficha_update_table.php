<?php
$dataProvider = new CArrayDataProvider($imagen, array(
	'keyField'=>'valor',
	'sort'=>array(
		'attributes'=>array(
			'contenido', 'valor', 'nombreCientifico', 'autor',
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
    'id'=>'atributo-imagen-asignados-grid',
    'dataProvider'=>$dataProvider,
	'enablePagination' => true,
    'columns'=>array(
    	array( 'name'=>'Id', 'value'=>'$data["valor"]', 'htmlOptions'=>array('width'=>'80')),
    	array( 'name'=>'Nombre', 'value'=>'CHtml::link($data["contenido"], Yii::app()->baseUrl."/imagen/".$data["contenido"], array("id"=>"single_catalog_image", "title"=>$data["nombreCientifico"]." - ".$data["autor"]))', 'type'=>'raw'),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{delete}',
			'htmlOptions'=>array('style'=>'width: 50px'),
			'deleteButtonUrl'=>'Yii::app()->createUrl("/catalogo/delete", array("idAtributo"=>$data["valor"]))',
		),
	),
));

?>