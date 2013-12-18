<?php

/**
 * This is the model class for table "referentegeografico".
 *
 * The followings are the available columns in table 'referentegeografico':
 * @property integer $id_referente_geografico
 * @property string $id_pais
 * @property string $id_sub
 * @property string $id_cm
 * @property string $poblacion_dane
 * @property string $intruccionesacceso
 * @property string $localidadhistorica
 *
 * The followings are the available model relations:
 * @property Contactos[] $contactoses
 * @property Ciudadmunicipio $idPais
 * @property Ciudadmunicipio $idSub
 * @property Ciudadmunicipio $idCm
 */
class Referentegeografico extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Referentegeografico the static model class
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
		return 'referentegeografico';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_referente_geografico', 'numerical', 'integerOnly'=>true),
			array('id_pais', 'length', 'max'=>2),
			array('id_sub', 'length', 'max'=>3),
			array('id_cm', 'length', 'max'=>8),
			array('poblacion_dane', 'length', 'max'=>10),
			array('intruccionesacceso, localidadhistorica', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_referente_geografico, id_pais, id_sub, id_cm, poblacion_dane, intruccionesacceso, localidadhistorica', 'safe', 'on'=>'search'),
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
			'contactoses' => array(self::HAS_MANY, 'Contactos', 'id_referente_geografico'),
			'idPais' => array(self::BELONGS_TO, 'Ciudadmunicipio', 'id_pais'),
			'idSub' => array(self::BELONGS_TO, 'Ciudadmunicipio', 'id_sub'),
			'idCm' => array(self::BELONGS_TO, 'Ciudadmunicipio', 'id_cm'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_referente_geografico' => 'Id Referente Geografico',
			'id_pais' => 'Id Pais',
			'id_sub' => 'Id Sub',
			'id_cm' => 'Id Cm',
			'poblacion_dane' => 'Poblacion Dane',
			'intruccionesacceso' => 'Intruccionesacceso',
			'localidadhistorica' => 'Localidadhistorica',
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

		$criteria->compare('id_referente_geografico',$this->id_referente_geografico);
		$criteria->compare('id_pais',$this->id_pais,true);
		$criteria->compare('id_sub',$this->id_sub,true);
		$criteria->compare('id_cm',$this->id_cm,true);
		$criteria->compare('poblacion_dane',$this->poblacion_dane,true);
		$criteria->compare('intruccionesacceso',$this->intruccionesacceso,true);
		$criteria->compare('localidadhistorica',$this->localidadhistorica,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}