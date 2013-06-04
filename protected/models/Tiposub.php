<?php

/**
 * This is the model class for table "tiposub".
 *
 * The followings are the available columns in table 'tiposub':
 * @property integer $tiposub_id
 * @property string $tiposub_nombre
 *
 * The followings are the available model relations:
 * @property Subadministrativa[] $subadministrativas
 */
class Tiposub extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tiposub the static model class
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
		return 'tiposub';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tiposub_id, tiposub_nombre', 'required'),
			array('tiposub_id', 'numerical', 'integerOnly'=>true),
			array('tiposub_nombre', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('tiposub_id, tiposub_nombre', 'safe', 'on'=>'search'),
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
			'subadministrativas' => array(self::HAS_MANY, 'Subadministrativa', 'tiposub_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tiposub_id' => 'Tiposub',
			'tiposub_nombre' => 'Tiposub Nombre',
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

		$criteria->compare('tiposub_id',$this->tiposub_id);
		$criteria->compare('tiposub_nombre',$this->tiposub_nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}