<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'striped bordered condensed',
	'id'=>'attribute-contactos-grid',
	'dataProvider'=>$contactosAttribute->search(),
	'filter'=>$contactosAttribute,
	'ajaxUrl'=>array('catalogo/updateajaxmodifytables'),
	'columns'=>array(
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{seleccionar}',
			'buttons'=>array (
				'seleccionar' => array (
					'label'=>'Seleccionar',
					'click'=>'js:function(){$("#Attribute_contacto_id").val($(this).parent().parent().children(":nth-child(2)").text());$("#Attribute_contacto_personaContacto").val($(this).parent().parent().children(":nth-child(3)").text());$("#Attribute_contacto_organizacionContacto").val($(this).parent().parent().children(":nth-child(4)").text());}'
				),
			),
		),
		array(
			'name'=>'contacto_id',
			'htmlOptions'=>array('width'=>'80'),
			'filter'=>CHtml::activeTextField($contactosAttribute, 'contacto_id', array('id'=>'Attribute_contacto_filter_id'))
		),
		array(
			'name'=>'persona',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'persona', array('id'=>'Attribute_contacto_filter_persona'))
		),
		array(
			'name'=>'organizacion',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'organizacion', array('id'=>'Attribute_contacto_filter_organizacion'))
		),
		array(
			'name'=>'cargo',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'cargo', array('id'=>'Attribute_contacto_filter_cargo'))
		),
		array(
			'name'=>'direccion',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'direccion', array('id'=>'Attribute_contacto_filter_direccion'))
		),
		array(
			'name'=>'telefono',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'telefono', array('id'=>'Attribute_contacto_filter_telefono'))
		),
		array(
			'name'=>'correo_electronico',
			'filter'=>CHtml::activeTextField($contactosAttribute, 'correo_electronico', array('id'=>'Attribute_contacto_filter_correo_electronico'))
		)
	)
)); ?>