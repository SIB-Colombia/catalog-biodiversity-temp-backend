<?php

/**
 * This is the model class for table "subadministrativa".
 *
 * The followings are the available columns in table 'subadministrativa':
 * @property string $pais_abreviatura
 * @property string $sub_abreviatura
 * @property integer $tiposub_id
 * @property string $sub_nombre
 * @property string $sa_dane
 *
 * The followings are the available model relations:
 * @property Ciudadmunicipio[] $ciudadmunicipios
 * @property Ciudadmunicipio[] $ciudadmunicipios1
 * @property Pais $paisAbreviatura
 * @property Tiposub $tiposub
 */
class Subadministrativa extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subadministrativa the static model class
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
		return 'subadministrativa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pais_abreviatura, sub_abreviatura, tiposub_id, sub_nombre', 'required'),
			array('tiposub_id', 'numerical', 'integerOnly'=>true),
			array('pais_abreviatura', 'length', 'max'=>2),
			array('sub_abreviatura', 'length', 'max'=>3),
			array('sub_nombre', 'length', 'max'=>50),
			array('sa_dane', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('pais_abreviatura, sub_abreviatura, tiposub_id, sub_nombre, sa_dane', 'safe', 'on'=>'search'),
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
			'ciudadmunicipios' => array(self::HAS_MANY, 'Ciudadmunicipio', 'pais_abreviatura'),
			'ciudadmunicipios1' => array(self::HAS_MANY, 'Ciudadmunicipio', 'sub_abreviatura'),
			'paisAbreviatura' => array(self::BELONGS_TO, 'Pais', 'pais_abreviatura'),
			'tiposub' => array(self::BELONGS_TO, 'Tiposub', 'tiposub_id'),
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
			'tiposub_id' => 'Tiposub',
			'sub_nombre' => 'Sub Nombre',
			'sa_dane' => 'Sa Dane',
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
		$criteria->compare('tiposub_id',$this->tiposub_id);
		$criteria->compare('sub_nombre',$this->sub_nombre,true);
		$criteria->compare('sa_dane',$this->sa_dane,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}