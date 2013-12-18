<?php

/**
 * This is the model class for table "estadoverificacion".
 *
 * The followings are the available columns in table 'estadoverificacion':
 * @property integer $estado_id
 * @property string $nombre
 * @property string $descripcion
 * @property boolean $valido_ind
 *
 * The followings are the available model relations:
 * @property Verificacionce[] $verificacionces
 */
class Estadoverificacion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Estadoverificacion the static model class
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
		return 'estadoverificacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, valido_ind', 'required'),
			array('nombre', 'length', 'max'=>50),
			array('descripcion', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('estado_id, nombre, descripcion, valido_ind', 'safe', 'on'=>'search'),
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
			'verificacionces' => array(self::HAS_MANY, 'Verificacionce', 'estado_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'estado_id' => 'Estado',
			'nombre' => 'Nombre',
			'descripcion' => 'Descripcion',
			'valido_ind' => 'Valido Ind',
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

		$criteria->compare('estado_id',$this->estado_id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('valido_ind',$this->valido_ind);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}