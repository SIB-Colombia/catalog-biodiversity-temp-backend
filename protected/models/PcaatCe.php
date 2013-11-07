<?php

/**
 * This is the model class for table "pcaat_ce".
 *
 * The followings are the available columns in table 'pcaat_ce':
 * @property integer $catalogoespecies_id
 * @property string $taxonnombre
 * @property string $taxoncompleto
 * @property string $paginaweb
 * @property string $autor
 *
 * The followings are the available model relations:
 * @property Catalogoespecies $catalogoespecies
 */
class PcaatCe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PcaatCe the static model class
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
		return 'pcaat_ce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id', 'required'),
			array('catalogoespecies_id', 'numerical', 'integerOnly'=>true),
			array('taxonnombre', 'length', 'max'=>100),
			array('taxoncompleto, paginaweb', 'length', 'max'=>255),
			array('autor', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('catalogoespecies_id, taxonnombre, taxoncompleto, paginaweb, autor', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'catalogoespecies_id' => 'ID de ficha',
			'taxonnombre' => 'Nombre científico',
			'taxoncompleto' => 'Taxon completo',
			'paginaweb' => 'Página Eeb',
			'autor' => 'Autor',
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
		$criteria->compare('taxonnombre',$this->taxonnombre,true);
		$criteria->compare('taxoncompleto',$this->taxoncompleto,true);
		$criteria->compare('paginaweb',$this->paginaweb,true);
		$criteria->compare('autor',$this->autor,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}