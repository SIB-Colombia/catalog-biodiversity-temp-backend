<?php

/**
 * This is the model class for table "regionesnaturales".
 *
 * The followings are the available columns in table 'regionesnaturales':
 * @property integer $id_region_natural
 * @property string $region_natural
 * @property string $descripcion
 *
 * The followings are the available model relations:
 * @property Catalogoespecies[] $catalogoespecies
 */
class Regionesnaturales extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Regionesnaturales the static model class
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
		return 'regionesnaturales';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('region_natural', 'required'),
			array('region_natural', 'length', 'max'=>255),
			array('descripcion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_region_natural, region_natural, descripcion', 'safe', 'on'=>'search'),
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
			'pcregionnaturalCes' => array(self::HAS_MANY, 'PcregionnaturalCe', 'id_region_natural'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_region_natural' => 'Id',
			'region_natural' => 'Región Natural',
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

		$criteria->compare('id_region_natural',$this->id_region_natural);
		$criteria->compare('LOWER(region_natural)',strtolower($this->region_natural),true);
		$criteria->compare('descripcion',$this->descripcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 'region_natural',
			)
		));
	}
}