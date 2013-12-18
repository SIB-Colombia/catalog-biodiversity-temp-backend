<?php

/**
 * This is the model class for table "pctesauros_ce".
 *
 * The followings are the available columns in table 'pctesauros_ce':
 * @property integer $id_tesauros
 * @property integer $catalogoespecies_id
 * @property string $tesauronombre
 * @property string $grupohumano
 * @property string $idioma
 * @property string $regionesgeograficas
 * @property string $paginaweb
 * @property string $tesaurocompleto
 *
 * The followings are the available model relations:
 * @property Catalogoespecies $catalogoespecies
 */
class PctesaurosCe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PctesaurosCe the static model class
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
		return 'pctesauros_ce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('catalogoespecies_id, tesauronombre', 'required'),
			array('id_tesauros, catalogoespecies_id', 'numerical', 'integerOnly'=>true),
			array('tesauronombre, grupohumano, idioma', 'length', 'max'=>50),
			array('regionesgeograficas', 'length', 'max'=>100),
			array('paginaweb, tesaurocompleto', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_tesauros, catalogoespecies_id, tesauronombre, grupohumano, idioma, regionesgeograficas, paginaweb, tesaurocompleto', 'safe', 'on'=>'search'),
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
			'id_tesauros' => 'Id tesauro',
			'catalogoespecies_id' => 'Id ficha catalogo',
			'tesauronombre' => 'Nombre común',
			'grupohumano' => 'Grupo humano',
			'idioma' => 'Idioma',
			'regionesgeograficas' => 'Región donde se usa',
			'paginaweb' => 'Enlace al término en el tesauro',
			'tesaurocompleto' => 'Tesauro completo',
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

		$criteria->compare('id_tesauros',$this->id_tesauros);
		$criteria->compare('catalogoespecies_id',$this->catalogoespecies_id);
		$criteria->compare('LOWER(tesauronombre)',strtolower($this->tesauronombre),true);
		$criteria->compare('LOWER(grupohumano)',strtolower($this->grupohumano),true);
		$criteria->compare('LOWER(idioma)',strtolower($this->idioma),true);
		$criteria->compare('LOWER(regionesgeograficas)',strtolower($this->regionesgeograficas),true);
		$criteria->compare('paginaweb',$this->paginaweb,true);
		$criteria->compare('LOWER(tesaurocompleto)',strtolower($this->tesaurocompleto),true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 'tesauronombre',
			),
		));
	}
}