<?php

/**
 * This is the model class for table "citaciontipo".
 *
 * The followings are the available columns in table 'citaciontipo':
 * @property integer $citaciontipo_id
 * @property string $citaciontipo_nombre
 * @property boolean $citacionsuperior_ind
 * @property boolean $serie_o_citacionsuperior_ind
 *
 * The followings are the available model relations:
 * @property Citacion[] $citacions
 */
class Citaciontipo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Citaciontipo the static model class
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
		return 'citaciontipo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('citaciontipo_id, citaciontipo_nombre, citacionsuperior_ind, serie_o_citacionsuperior_ind', 'required'),
			array('citaciontipo_id', 'numerical', 'integerOnly'=>true),
			array('citaciontipo_nombre', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('citaciontipo_id, citaciontipo_nombre, citacionsuperior_ind, serie_o_citacionsuperior_ind', 'safe', 'on'=>'search'),
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
			'citacions' => array(self::HAS_MANY, 'Citacion', 'citaciontipo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'citaciontipo_id' => 'Citaciontipo',
			'citaciontipo_nombre' => 'Citaciontipo Nombre',
			'citacionsuperior_ind' => 'Citacionsuperior Ind',
			'serie_o_citacionsuperior_ind' => 'Serie O Citacionsuperior Ind',
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

		$criteria->compare('citaciontipo_id',$this->citaciontipo_id);
		$criteria->compare('citaciontipo_nombre',$this->citaciontipo_nombre,true);
		$criteria->compare('citacionsuperior_ind',$this->citacionsuperior_ind);
		$criteria->compare('serie_o_citacionsuperior_ind',$this->serie_o_citacionsuperior_ind);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}