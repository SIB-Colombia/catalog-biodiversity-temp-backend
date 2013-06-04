<?php

/**
 * This is the model class for table "citacion".
 *
 * The followings are the available columns in table 'citacion':
 * @property integer $citacion_id
 * @property integer $citaciontipo_id
 * @property boolean $sistemaclasificacion_ind
 * @property string $fecha
 * @property string $documento_titulo
 * @property string $autor
 * @property string $editor
 * @property string $publicador
 * @property string $editorial
 * @property string $lugar_publicacion
 * @property string $edicion_version
 * @property string $volumen
 * @property string $serie
 * @property string $numero
 * @property string $paginas
 * @property string $hipervinculo
 * @property string $fecha_actualizacion
 * @property string $fecha_consulta
 * @property integer $citacion_superior_id
 * @property integer $repositorio_citacion
 * @property string $otros
 *
 * The followings are the available model relations:
 * @property Citacion $citacionSuperior
 * @property Citacion[] $citacions
 * @property Citaciontipo $citaciontipo
 * @property Contactos $repositorioCitacion
 */
class Citacion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Citacion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'citacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('citaciontipo_id, sistemaclasificacion_ind, fecha, documento_titulo', 'required'),
			array('citacion_id, citaciontipo_id, citacion_superior_id, repositorio_citacion', 'numerical', 'integerOnly'=>true),
			array('fecha', 'length', 'max'=>12),
			array('documento_titulo, lugar_publicacion, serie', 'length', 'max'=>255),
			array('autor, editor, hipervinculo', 'length', 'max'=>100),
			array('publicador', 'length', 'max'=>190),
			array('editorial', 'length', 'max'=>70),
			array('edicion_version, numero, paginas', 'length', 'max'=>25),
			array('volumen', 'length', 'max'=>50),
			array('fecha_actualizacion, fecha_consulta, otros', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('citacion_id, citaciontipo_id, sistemaclasificacion_ind, fecha, documento_titulo, autor, editor, publicador, editorial, lugar_publicacion, edicion_version, volumen, serie, numero, paginas, hipervinculo, fecha_actualizacion, fecha_consulta, citacion_superior_id, repositorio_citacion, otros', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'citacionSuperior' => array(self::BELONGS_TO, 'Citacion', 'citacion_superior_id'),
			'citacions' => array(self::HAS_MANY, 'Citacion', 'citacion_superior_id'),
			'citaciontipo' => array(self::BELONGS_TO, 'Citaciontipo', 'citaciontipo_id'),
			'repositorioCitacion' => array(self::BELONGS_TO, 'Contactos', 'repositorio_citacion'),
			'catalogoespecies' => array(self::HAS_MANY, 'Catalogoespecies', 'citacion_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'citacion_id' => 'Citación',
			'citaciontipo_id' => 'Tipo de citación',
			'sistemaclasificacion_ind' => 'Sistema Clasificacion Ind',
			'fecha' => 'Fecha',
			'documento_titulo' => 'Documento Título',
			'autor' => 'Autor',
			'editor' => 'Editor',
			'publicador' => 'Publicador',
			'editorial' => 'Editorial',
			'lugar_publicacion' => 'Lugar Publicación',
			'edicion_version' => 'Edición Versión',
			'volumen' => 'Volumen',
			'serie' => 'Serie',
			'numero' => 'Número',
			'paginas' => 'Páginas',
			'hipervinculo' => 'Hipervínculo',
			'fecha_actualizacion' => 'Fecha Actualización',
			'fecha_consulta' => 'Fecha Consulta',
			'citacion_superior_id' => 'Referencia superior',
			'repositorio_citacion' => 'Repositorio de la citación',
			'otros' => 'Otros',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('citacion_id',$this->citacion_id);
		$criteria->compare('citaciontipo_id',$this->citaciontipo_id);
		$criteria->compare('sistemaclasificacion_ind',$this->sistemaclasificacion_ind);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('LOWER(documento_titulo)',strtolower($this->documento_titulo),true);
		$criteria->compare('LOWER(autor)',strtolower($this->autor),true);
		$criteria->compare('editor',$this->editor,true);
		$criteria->compare('publicador',$this->publicador,true);
		$criteria->compare('editorial',$this->editorial,true);
		$criteria->compare('lugar_publicacion',$this->lugar_publicacion,true);
		$criteria->compare('edicion_version',$this->edicion_version,true);
		$criteria->compare('volumen',$this->volumen,true);
		$criteria->compare('serie',$this->serie,true);
		$criteria->compare('numero',$this->numero,true);
		$criteria->compare('paginas',$this->paginas,true);
		$criteria->compare('hipervinculo',$this->hipervinculo,true);
		$criteria->compare('fecha_actualizacion',$this->fecha_actualizacion,true);
		$criteria->compare('fecha_consulta',$this->fecha_consulta,true);
		$criteria->compare('citacion_superior_id',$this->citacion_superior_id);
		$criteria->compare('repositorio_citacion',$this->repositorio_citacion);
		$criteria->compare('otros',$this->otros,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function ListarTiposCitaciones()
	{
		return CHtml::listData(Citaciontipo::model()->findAll(), 'citaciontipo_id','citaciontipo_nombre');
	}
	
	public function ListarReferenciasSuperiores()
	{
		$criteria=new CDbCriteria;
		$criteria->order='autor';
		$referenciasSuperioresSearch = Citacion::model()->findAll($criteria);
		return CHtml::listData($referenciasSuperioresSearch, 'citacion_id', 'referenciaSuperiorConcatenada');
	}
	
	public function ListarRepositorioCitacion()
	{
		$criteria=new CDbCriteria;
		$criteria->order='persona';
		$repositorioCitacionSearch = Contactos::model()->findAll($criteria);
		return CHtml::listData($repositorioCitacionSearch, 'contacto_id', 'persona');
	}
	
	public function getReferenciaSuperiorConcatenada() {
		return $this->autor.', '.$this->fecha.', '.$this->documento_titulo.', '.$this->editor.', '.$this->publicador.', '.$this->editorial;
	}
}