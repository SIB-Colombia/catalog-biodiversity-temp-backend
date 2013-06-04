<?php

/**
 * This is the model class for table "ciudadmunicipio".
 *
 * The followings are the available columns in table 'ciudadmunicipio':
 * @property string $pais_abreviatura
 * @property string $sub_abreviatura
 * @property string $ciudad_municipio_abreviatura
 * @property string $ciudad_municipio_nombre
 * @property string $cm_dane
 *
 * The followings are the available model relations:
 * @property Subadministrativa $paisAbreviatura
 * @property Subadministrativa $subAbreviatura
 * @property Referentegeografico[] $referentegeograficos
 * @property Referentegeografico[] $referentegeograficos1
 * @property Referentegeografico[] $referentegeograficos2
 */
class Ciudadmunicipio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ciudadmunicipio the static model class
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
		return 'ciudadmunicipio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pais_abreviatura, sub_abreviatura, ciudad_municipio_abreviatura, ciudad_municipio_nombre', 'required'),
			array('pais_abreviatura', 'length', 'max'=>2),
			array('sub_abreviatura', 'length', 'max'=>3),
			array('ciudad_municipio_abreviatura', 'length', 'max'=>8),
			array('ciudad_municipio_nombre', 'length', 'max'=>50),
			array('cm_dane', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('pais_abreviatura, sub_abreviatura, ciudad_municipio_abreviatura, ciudad_municipio_nombre, cm_dane', 'safe', 'on'=>'search'),
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
			'paisAbreviatura' => array(self::BELONGS_TO, 'Subadministrativa', 'pais_abreviatura'),
			'subAbreviatura' => array(self::BELONGS_TO, 'Subadministrativa', 'sub_abreviatura'),
			'referentegeograficos' => array(self::HAS_MANY, 'Referentegeografico', 'id_pais'),
			'referentegeograficos1' => array(self::HAS_MANY, 'Referentegeografico', 'id_sub'),
			'referentegeograficos2' => array(self::HAS_MANY, 'Referentegeografico', 'id_cm'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pais_abreviatura' => 'Pais Abreviatura',
			'sub_abreviatura' => 'Sub Abreviatura',
			'ciudad_municipio_abreviatura' => 'Ciudad Municipio Abreviatura',
			'ciudad_municipio_nombre' => 'Ciudad Municipio Nombre',
			'cm_dane' => 'Cm Dane',
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

		$criteria->compare('pais_abreviatura',$this->pais_abreviatura,true);
		$criteria->compare('sub_abreviatura',$this->sub_abreviatura,true);
		$criteria->compare('ciudad_municipio_abreviatura',$this->ciudad_municipio_abreviatura,true);
		$criteria->compare('ciudad_municipio_nombre',$this->ciudad_municipio_nombre,true);
		$criteria->compare('cm_dane',$this->cm_dane,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}