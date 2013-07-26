<?php

/**
 * This is the model class for table "catalogo_user".
 *
 * The followings are the available columns in table 'catalogo_user':
 * @property string $username
 * @property string $password
 * @property integer $contacto_id
 * @property string $role
 */
class CatalogoUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CatalogoUser the static model class
	 */
	public  $newpassword;
	public  $password2;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'catalogo_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password,role,contacto_id', 'required'),
			array('contacto_id', 'numerical', 'integerOnly'=>true),
			array('username, password, password2, role', 'length', 'max'=>32),
			array('password', 'compare', 'compareAttribute'=>'password2', 'on'=>'insert'),
			array('newpassword', 'compare', 'compareAttribute'=>'password2', 'on'=>'update'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, password, contacto_id, role', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Usuario',
			'password' => 'Password',
			'newpassword' => 'Nuevo Password',
			'password2' => 'Confirmar Password',
			'contacto_id' => 'Contacto',
			'role' => 'Rol',
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

		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('contacto_id',$this->contacto_id);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function ListarContactos()
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition('correo_electronico != \'\'');
		$criteria->order='persona';
		$contactosSearch = Contactos::model()->findAll($criteria);
		return CHtml::listData($contactosSearch, 'contacto_id', 'persona');
	}
}