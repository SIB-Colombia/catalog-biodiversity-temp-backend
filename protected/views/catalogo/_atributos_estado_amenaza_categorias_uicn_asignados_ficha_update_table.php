<?php
//yii::log(CVarDumper::dumpAsString($estadoAmenazaCategoriasUICN["En Colombia"]), CLogger::LEVEL_INFO);
if (!empty($estadoAmenazaCategoriasUICN["En Colombia"])) {
	$box = $this->beginWidget('bootstrap.widgets.TbBox', array(
		'title' => 'En Colombia',
		'headerIcon' => 'icon-arrow-right',
		'htmlOptions' => array('class'=>'bootstrap-widget-table'),
	));

	$dataProviderColombia = new CArrayDataProvider($estadoAmenazaCategoriasUICN["En Colombia"], array(
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
		'id'=>'atributo-estado-cites-asignados-grid',
		'dataProvider'=>$dataProviderColombia,
		'enablePagination' => true,
		'columns'=>array(
			array( 'name'=>'Id', 'value'=>'$data["valor"]', 'htmlOptions'=>array('width'=>'80')),
			array( 'name'=>'Nombre', 'value'=>'$data["contenido"]', 'type'=>'raw'),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'template'=>'{delete}',
				'htmlOptions'=>array('style'=>'width: 50px'),
				'buttons'=>array (
					'delete' => array (
						'label'=>'Borrar',
						'url'=>'Yii::app()->createUrl("/catalogo/deleteattribute", array("idAtributo"=>$data["valor"], "atributovalor_id"=>$data["ceatributovalor_id"]))',
					),
				),
			),
		),
	));
	$this->endWidget();
}

if (!empty($estadoAmenazaCategoriasUICN["En el mundo"])) {
	$box = $this->beginWidget('bootstrap.widgets.TbBox', array(
		'title' => 'En el mundo',
		'headerIcon' => 'icon-arrow-right',
		'htmlOptions' => array('class'=>'bootstrap-widget-table'),
	));

	$dataProviderMundo = new CArrayDataProvider($estadoAmenazaCategoriasUICN["En el mundo"], array(
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
		'id'=>'atributo-estado-cites-asignados-grid',
		'dataProvider'=>$dataProviderMundo,
		'enablePagination' => true,
		'columns'=>array(
			array( 'name'=>'Id', 'value'=>'$data["valor"]', 'htmlOptions'=>array('width'=>'80')),
			array( 'name'=>'Nombre', 'value'=>'$data["contenido"]', 'type'=>'raw'),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'template'=>'{delete}',
				'htmlOptions'=>array('style'=>'width: 50px'),
				'buttons'=>array (
					'delete' => array (
						'label'=>'Borrar',
						'url'=>'Yii::app()->createUrl("/catalogo/deleteattribute", array("idAtributo"=>$data["valor"], "atributovalor_id"=>$data["ceatributovalor_id"))',
					),
				),
			),
		),
	));
	$this->endWidget();
}

?>