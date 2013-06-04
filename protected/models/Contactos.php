<?php

/**
 * This is the model class for table "contactos".
 *
 * The followings are the available columns in table 'contactos':
 * @property integer $contacto_id
 * @property string $direccion
 * @property integer $id_referente_geografico
 * @property string $telefono
 * @property string $acronimo
 * @property string $persona
 * @property string $fax
 * @property string $correo_electronico
 * @property string $organizacion
 * @property string $cargo
 * @property string $instrucciones
 * @property string $hora_inicial
 * @property string $hora_final
 *
 * The followings are the available model relations:
 * @property Referentegeografico $idReferenteGeografico
 * @property Catalogoespecies[] $catalogoespecies
 * @property Citacion[] $citacions
 */
class Contactos extends CActiveRecord
{
	private $_idPais;
	private $_idDepartamentoEstadoProvincia;
	private $_idMunicipio;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contactos the static model class
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
		return 'contactos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('persona, direccion, idPais, idDepartamentoEstadoProvincia, idMunicipio', 'required'),
			array('contacto_id, id_referente_geografico', 'numerical', 'integerOnly'=>true),
			array('direccion, telefono', 'length', 'max'=>100),
			array('acronimo, persona', 'length', 'max'=>50),
			array('fax, correo_electronico, organizacion, cargo, instrucciones', 'length', 'max'=>255),
			array('hora_inicial, hora_final', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('contacto_id, direccion, id_referente_geografico, telefono, acronimo, persona, fax, correo_electronico, organizacion, cargo, instrucciones, hora_inicial, hora_final', 'safe', 'on'=>'search'),
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
			'idReferenteGeografico' => array(self::BELONGS_TO, 'Referentegeografico', 'id_referente_geografico'),
			'catalogoespecies' => array(self::HAS_MANY, 'Catalogoespecies', 'contacto_id'),
			'citacions' => array(self::HAS_MANY, 'Citacion', 'repositorio_citacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'contacto_id' => 'Id contacto',
			'direccion' => 'Dirección física',
			'id_referente_geografico' => 'Id referente geografico',
			'telefono' => 'Teléfono',
			'acronimo' => 'Sigla o acrónimo',
			'persona' => 'Nombre',
			'fax' => 'Fax',
			'correo_electronico' => 'Correo electrónico',
			'organizacion' => 'Organización',
			'cargo' => 'Cargo u ocupación',
			'instrucciones' => 'Instrucciones de contacto',
			'hora_inicial' => 'Disponibilidad - hora inicial',
			'hora_final' => 'Disponibilidad - hora final',
			'idPaid' => 'País',
			'idDepartamentoEstadoProvincia' => 'Departamento, estado o provincia',
			'idMunicipio' => 'Municipio',
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

		$criteria->compare('contacto_id',$this->contacto_id);
		$criteria->compare('LOWER(direccion)',strtolower($this->direccion),true);
		$criteria->compare('id_referente_geografico',$this->id_referente_geografico);
		$criteria->compare('LOWER(telefono)',strtolower($this->telefono),true);
		$criteria->compare('acronimo',$this->acronimo,true);
		$criteria->compare('LOWER(persona)',strtolower($this->persona),true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('LOWER(correo_electronico)',strtolower($this->correo_electronico),true);
		$criteria->compare('LOWER(organizacion)',strtolower($this->organizacion),true);
		$criteria->compare('LOWER(cargo)',strtolower($this->cargo),true);
		$criteria->compare('instrucciones',$this->instrucciones,true);
		$criteria->compare('hora_inicial',$this->hora_inicial,true);
		$criteria->compare('hora_final',$this->hora_final,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function ListarPaises()
	{
		$criteria=new CDbCriteria;
		$criteria->order='pais_nombre';
		$paisesSearch = Pais::model()->findAll($criteria);
		return CHtml::listData($paisesSearch, 'pais_abreviatura', 'pais_nombre');
	}
	
	public function ListarDepartamentosEstadosProvincias()
	{
		$criteria=new CDbCriteria;
		$criteria->order='sub_nombre';
		//$criteria->group='pais_abreviatura, sub_abreviatura, sub_nombre';
		$criteria->distinct=true;
		if($this->_idPais) {
			$criteria->addCondition('pais_abreviatura=:idPais');
			$criteria->params[':idPais'] = $this->_idPais;
		} else {
			$criteria->addCondition('pais_abreviatura is null');
		}
		$departamentosEstadosProvinciasSearch = Subadministrativa::model()->findAll($criteria);
		return CHtml::listData($departamentosEstadosProvinciasSearch, 'sub_abreviatura', 'sub_nombre');
	}
	
	public function ListarMunicipios()
	{
		$criteria=new CDbCriteria;
		$criteria->order='ciudad_municipio_nombre';
		//$criteria->group='pais_abreviatura, sub_abreviatura, ciudad_municipio_abreviatura, ciudad_municipio_nombre';
		$criteria->distinct=true;
		if($this->_idPais && $this->_idDepartamentoEstadoProvincia) {
			$criteria->addCondition('pais_abreviatura=:idPais');
			$criteria->params[':idPais'] = $this->_idPais;
			$criteria->addCondition('sub_abreviatura=:idDepartamento');
			$criteria->params[':idDepartamento'] = $this->_idDepartamentoEstadoProvincia;
		} else {
			$criteria->addCondition('pais_abreviatura is null');
			$criteria->addCondition('sub_abreviatura is null');
		}
		$ciudadMunicipioSearch = Ciudadmunicipio::model()->findAll($criteria);
		return CHtml::listData($ciudadMunicipioSearch, 'ciudad_municipio_abreviatura', 'ciudad_municipio_nombre');
	}
	
	public function getIdPais() {
		return $this->_idPais;
	}
	
	public function setIdPais($value)
	{
		$this->_idPais = $value;
	}
	
	public function getIdDepartamentoEstadoProvincia() {
		return $this->_idDepartamentoEstadoProvincia;
	}
	
	public function setIdDepartamentoEstadoProvincia($value)
	{
		$this->_idDepartamentoEstadoProvincia = $value;
	}
	
	public function getIdMunicipio() {
		return $this->_idMunicipio;
	}
	
	public function setIdMunicipio($value)
	{
		$this->_idMunicipio = $value;
	}
}