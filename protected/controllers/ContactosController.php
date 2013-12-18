<?php

class ContactosController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update', 'actualizarDepartamento', 'actualizarMunicipio'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','actualizarDepartamento', 'actualizarMunicipio'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Contactos;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Contactos']))
		{	
			if($_POST['Contactos']['idPais'] == '' || $_POST['Contactos']['idDepartamentoEstadoProvincia'] == '' || $_POST['Contactos']['idMunicipio'] == '') {
				echo CJSON::encode(array(
						'status'=>'failure',
						'respuesta'=>$this->renderPartial('_form', array('model'=>$model), true)));
				exit;
			}			
			// Verificar si existe referente geográfico correspondiente a país, departamento y municipio
			$criteria=new CDbCriteria;
			$criteria->addCondition('id_pais=:idPais AND id_sub=:idSub AND id_cm=:idCm');
			$criteria->params[':idPais'] = $_POST['Contactos']['idPais'];
			$criteria->params[':idSub'] = $_POST['Contactos']['idDepartamentoEstadoProvincia'];
			$criteria->params[':idCm'] = $_POST['Contactos']['idMunicipio'];
			$datos = Referentegeografico::model()->find($criteria);
			if(isset($datos)) {
				// Ya existe este referente geográfico, por lo tanto usamos su id
				$model->id_referente_geografico=$datos->id_referente_geografico;
			} else {
				// No existe este referente geográfico
				$modelReferenteGeografico = new Referentegeografico();
				$modelReferenteGeografico->id_pais=$_POST['Contactos']['idPais'];
				$modelReferenteGeografico->id_sub=$_POST['Contactos']['idDepartamentoEstadoProvincia'];
				$modelReferenteGeografico->id_cm=$_POST['Contactos']['idMunicipio'];
				//$modelReferenteGeografico->validate();
				//yii::log(var_dump($modelReferenteGeografico->getErrors()));
				//yii::log($modelReferenteGeografico->getErrors());
				if($modelReferenteGeografico->save()) {
					$model->id_referente_geografico=$modelReferenteGeografico->id_referente_geografico;
				}
			}
			
			$model->attributes=$_POST['Contactos'];
			//$model->validate();
			//yii::log(var_dump($model->getErrors()));
			//yii::log($model->getErrors());
			
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
							'status'=>'success',
							'respuesta'=>"Contacto guardado exitosamente.",
							'idContacto'=>$model->contacto_id,
							'nombreContacto'=>$model->persona,
							'organizacion'=>$model->organizacion,
					));
					exit;
				} else {
					$this->redirect(array('view','id'=>$model->contacto_id));
				}
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
					'status'=>'failure',
					'respuesta'=>$this->renderPartial('_form', array('model'=>$model), true)));
			exit;
		} else {
			$date = date('H:i');
			//$date = new DateTime();
			//$model->hora_inicial=date_format($date, 'H:i');
			//$model->hora_final=date_format($date, 'H:i');
			$model->hora_inicial = $date;
			$model->hora_final = $date;
			$this->render('create',array('model'=>$model,));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Contactos']))
		{
			if($_POST['Contactos']['idPais'] == '' || $_POST['Contactos']['idDepartamentoEstadoProvincia'] == '' || $_POST['Contactos']['idMunicipio'] == '') {
				echo CJSON::encode(array(
						'status'=>'failure',
						'respuesta'=>$this->renderPartial('_form', array('model'=>$model), true)));
				exit;
			}
			// Verificar si existe referente geográfico correspondiente a país, departamento y municipio
			$criteria=new CDbCriteria;
			$criteria->addCondition('id_pais=:idPais AND id_sub=:idSub AND id_cm=:idCm');
			$criteria->params[':idPais'] = $_POST['Contactos']['idPais'];
			$criteria->params[':idSub'] = $_POST['Contactos']['idDepartamentoEstadoProvincia'];
			$criteria->params[':idCm'] = $_POST['Contactos']['idMunicipio'];
			$datos = Referentegeografico::model()->find($criteria);
			if(isset($datos)) {
				// Ya existe este referente geográfico, por lo tanto usamos su id
				$model->id_referente_geografico=$datos->id_referente_geografico;
			} else {
				// No existe este referente geográfico
				$modelReferenteGeografico = new Referentegeografico();
				$modelReferenteGeografico->id_pais=$_POST['Contactos']['idPais'];
				$modelReferenteGeografico->id_sub=$_POST['Contactos']['idDepartamentoEstadoProvincia'];
				$modelReferenteGeografico->id_cm=$_POST['Contactos']['idMunicipio'];
				if($modelReferenteGeografico->save()) {
					$model->id_referente_geografico=$modelReferenteGeografico->id_referente_geografico;
				}
			}
				
			$model->attributes=$_POST['Contactos'];
				
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
							'status'=>'success',
							'respuesta'=>"Contacto guardado exitosamente.",
							'idContacto'=>$model->contacto_id,
							'nombreContacto'=>$model->persona,
							'organizacion'=>$model->organizacion,
					));
					exit;
				} else {
					$this->redirect(array('view','id'=>$model->contacto_id));
				}
			}
		}
		
		// completar datos de país, departamento y municipio
		$model->idPais=$model->idReferenteGeografico->id_pais;
		$model->idDepartamentoEstadoProvincia=$model->idReferenteGeografico->id_sub;
		$model->idMunicipio=$model->idReferenteGeografico->id_cm;
		
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
					'status'=>'failure',
					'respuesta'=>$this->renderPartial('_form', array('model'=>$model), true)));
			exit;
		} else {
			$this->render('update',array('model'=>$model,));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Contactos');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Contactos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contactos']))
			$model->attributes=$_GET['Contactos'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionActualizarDepartamento() {
		$criteria=new CDbCriteria;
		$criteria->order='sub_nombre';
		//$criteria->group='pais_abreviatura, sub_abreviatura, sub_nombre';
		$criteria->distinct=true;
		$criteria->addCondition('pais_abreviatura=:idPais');
		$criteria->params[':idPais'] = $_POST['Contactos']['idPais'];
		$datos = Subadministrativa::model()->findAll($criteria);
			
		$datos=CHtml::listData($datos,'sub_abreviatura','sub_nombre');
		foreach($datos as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}
	
	public function actionActualizarMunicipio() {
		$criteria=new CDbCriteria;
		$criteria->order='ciudad_municipio_nombre';
		//$criteria->group='pais_abreviatura, sub_abreviatura, ciudad_municipio_abreviatura, ciudad_municipio_nombre';
		$criteria->distinct=true;
		$criteria->addCondition('pais_abreviatura=:idPais');
		$criteria->params[':idPais'] = $_POST['Contactos']['idPais'];
		$criteria->addCondition('sub_abreviatura=:idDepartamentoEstadoProvincia');
		$criteria->params[':idDepartamentoEstadoProvincia'] = $_POST['Contactos']['idDepartamentoEstadoProvincia'];
		$datos = Ciudadmunicipio::model()->findAll($criteria);
			
		$datos=CHtml::listData($datos,'ciudad_municipio_abreviatura','ciudad_municipio_nombre');
		foreach($datos as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Contactos the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Contactos::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Contactos $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='contactos-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
