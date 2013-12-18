<?php

/**
 * This is the model class for table "pcdepartamentos_ce".
 *
 * The followings are the available columns in table 'pcdepartamentos_ce':
 * @property integer $catalogoespecies_id
 * @property integer $id_departamento
 * @property string $sub_nombre
 */
class PcdepartamentosCe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PcdepartamentosCe the static model class
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
		return 'pcdepartamentos_ce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id, id_departamento', 'required'),
			array('catalogoespecies_id, id_departamento', 'numerical', 'integerOnly'=>true),
			array('sub_nombre', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('catalogoespecies_id, id_departamento, sub_nombre', 'safe', 'on'=>'search'),
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
			'departamento' => array(self::BELONGS_TO, 'Departamentos', 'id_departamento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'catalogoespecies_id' => 'Catalogoespecies',
			'id_departamento' => 'Id Departamento',
			'sub_nombre' => 'Sub Nombre',
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
		$criteria->compare('id_departamento',$this->id_departamento);
		$criteria->compare('sub_nombre',$this->sub_nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}