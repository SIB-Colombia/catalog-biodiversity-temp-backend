<h5>Seleccione la referencia y luego oprima en el boton guardar para adicionarla a la ficha</h5>
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'attribute-referenciabibliografica-grid',
    'dataProvider'=>$attributeReferenciasBibliograficas->search(),
    'filter'=>$attributeReferenciasBibliograficas,
    'ajaxUrl'=>array('catalogo/updateajaxmodifytables'),
    'columns'=>array(
    	array(
    		'class'=>'bootstrap.widgets.TbButtonColumn',
    		'template'=>'{seleccionar}',
    		'buttons'=>array (
    			'seleccionar' => array (
    				'label'=>'Seleccionar',
    				'click'=>'js:function(){$("#Attribute_referencia_bibliografica_id").val($(this).parent().parent().children(":nth-child(2)").text());$("#Attribute_referencia_bibliografica_tituloCita").val($(this).parent().parent().children(":nth-child(4)").text());$("#Attribute_referencia_bibliografica_autorCita").val($(this).parent().parent().children(":nth-child(5)").text());}'
    			),
    		),
    	),
    	array( 
            'name'=>'citacion_id', 
            'htmlOptions'=>array('width'=>'80'),
            'filter'=>CHtml::activeTextField($attributeReferenciasBibliograficas, 'citacion_id', array('id'=>'Attribute_referencia_bibliografica_filter_id'))
        ),
		array(
            'name'=>'fecha',
            'filter'=>CHtml::activeTextField($attributeReferenciasBibliograficas, 'fecha', array('id'=>'Attribute_referencia_bibliografica_filter_fecha'))
        ),
        array(
            'name'=>'documento_titulo',
            'filter'=>CHtml::activeTextField($attributeReferenciasBibliograficas, 'documento_titulo', array('id'=>'Attribute_referencia_bibliografica_filter_documento_titulo'))
        ),
        array(
            'name'=>'autor',
            'filter'=>CHtml::activeTextField($attributeReferenciasBibliograficas, 'autor', array('id'=>'Attribute_referencia_bibliografica_filter_autor'))
        )
	),
)); ?>