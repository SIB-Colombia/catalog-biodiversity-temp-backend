<?php

/**
 * This is the model class for table "departamentos".
 *
 * The followings are the available columns in table 'departamentos':
 * @property integer $id_departamento
 * @property string $departamento
 * @property string $descripcion
 *
 * The followings are the available model relations:
 * @property Catalogoespecies[] $catalogoespecies
 */
class Departamentos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Departamentos the static model class
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
		return 'departamentos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('departamento', 'required'),
			array('departamento', 'length', 'max'=>255),
			array('descripcion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_departamento, departamento, descripcion', 'safe', 'on'=>'search'),
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
			'pcdepartamentosCes' => array(self::HAS_MANY, 'PcdepartamentosCe', 'id_departamento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_departamento' => 'Id',
			'departamento' => 'Departamento',
			'descripcion' => 'Descripción',
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

		$criteria->compare('id_departamento',$this->id_departamento);
		$criteria->compare('LOWER(departamento)',strtolower($this->departamento),true);
		$criteria->compare('descripcion',$this->descripcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 'departamento',
			)
		));
	}
}