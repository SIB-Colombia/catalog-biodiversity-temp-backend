<?php

/**
 * This is the model class for table "verificacionce".
 *
 * The followings are the available columns in table 'verificacionce':
 * @property integer $catalogoespecies_id
 * @property string $contacto_id
 * @property string $contactoresponsable_id
 * @property integer $estado_id
 * @property string $fecha
 * @property string $comentarios
 *
 * The followings are the available model relations:
 * @property Catalogoespecies $catalogoespecies
 * @property Estadoverificacion $estado
 */
class Verificacionce extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Verificacionce the static model class
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
		return 'verificacionce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id, contacto_id, contactoresponsable_id, estado_id, fecha', 'required'),
			array('catalogoespecies_id, estado_id', 'numerical', 'integerOnly'=>true),
			array('comentarios', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('catalogoespecies_id, contacto_id, contactoresponsable_id, estado_id, fecha, comentarios', 'safe', 'on'=>'search'),
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
			'catalogoespecies' => array(self::BELONGS_TO, 'Catalogoespecies', 'catalogoespecies_id'),
			'estado' => array(self::BELONGS_TO, 'Estadoverificacion', 'estado_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'catalogoespecies_id' => 'Catalogoespecies',
			'contacto_id' => 'Contacto',
			'contactoresponsable_id' => 'Contactoresponsable',
			'estado_id' => 'Estado',
			'fecha' => 'Fecha de la última verificación',
			'comentarios' => 'Comentarios',
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

		$criteria->compare('catalogoespecies_id',$this->catalogoespecies_id);
		$criteria->compare('contacto_id',$this->contacto_id,true);
		$criteria->compare('contactoresponsable_id',$this->contactoresponsable_id,true);
		$criteria->compare('estado_id',$this->estado_id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('comentarios',$this->comentarios,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}