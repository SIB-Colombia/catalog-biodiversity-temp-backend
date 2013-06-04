<?php

/**
 * This is the model class for table "pcregionnatural_ce".
 *
 * The followings are the available columns in table 'pcregionnatural_ce':
 * @property integer $catalogoespecies_id
 * @property string $regionnatural
 * @property integer $id_region_natural
 */
class PcregionnaturalCe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PcregionnaturalCe the static model class
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
		return 'pcregionnatural_ce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id, regionnatural, id_region_natural', 'required'),
			array('catalogoespecies_id, id_region_natural', 'numerical', 'integerOnly'=>true),
			array('regionnatural', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('catalogoespecies_id, regionnatural, id_region_natural', 'safe', 'on'=>'search'),
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
			'region_natural' => array(self::BELONGS_TO, 'Regionesnaturales', 'id_region_natural'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'catalogoespecies_id' => 'ID Catalogo',
			'regionnatural' => 'Region natural',
			'id_region_natural' => 'Id',
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
		$criteria->compare('regionnatural',$this->regionnatural,true);
		$criteria->compare('id_region_natural',$this->id_region_natural);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}