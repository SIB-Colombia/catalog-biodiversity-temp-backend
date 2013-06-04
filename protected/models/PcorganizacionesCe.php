<?php

/**
 * This is the model class for table "pcorganizaciones_ce".
 *
 * The followings are the available columns in table 'pcorganizaciones_ce':
 * @property integer $catalogoespecies_id
 * @property string $organizaciones
 * @property integer $id_organizacion
 */
class PcorganizacionesCe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PcorganizacionesCe the static model class
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
		return 'pcorganizaciones_ce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id, organizaciones, id_organizacion', 'required'),
			array('catalogoespecies_id, id_organizacion', 'numerical', 'integerOnly'=>true),
			array('organizaciones', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('catalogoespecies_id, organizaciones, id_organizacion', 'safe', 'on'=>'search'),
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
			'organizacion' => array(self::BELONGS_TO, 'Organizaciones', 'id_organizacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'catalogoespecies_id' => 'Id catalogo',
			'organizaciones' => 'Organización',
			'id_organizacion' => 'Id',
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
		$criteria->compare('organizaciones',$this->organizaciones,true);
		$criteria->compare('id_organizacion',$this->id_organizacion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}