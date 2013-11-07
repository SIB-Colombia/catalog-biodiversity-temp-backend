<?php

class PctesaurosCeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','includecommonname'),
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
		$model=new PctesaurosCe;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PctesaurosCe']))
		{
			$model->attributes=$_POST['PctesaurosCe'];
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest) {
						echo CJSON::encode(array(
							'status'=>'success',
							'respuesta'=>"Nombre común guardado exitosamente."
						));
						exit;
				} else {
					$this->redirect(array('view','id'=>$model->id_tesauros));
				}
			}
		}
		
		if (Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['catalogoId'])) {
				$model->catalogoespecies_id=$_POST['catalogoId'];
				echo CJSON::encode(array(
					'status'=>'failure',
					'respuesta'=>$this->renderPartial('_form', array('model'=>$model), true)));
				exit;
			}
		} else {
			$this->render('create',array('model'=>$model,));
		}
	}
	
	public function actionIncludecommonname() {
		if (Yii::app()->request->isAjaxRequest) {
			$request = Yii::app()->getRequest();
			if($request->getIsPostRequest()){
				if(isset($_POST['ids'])){
					$ids = explode(',',$_POST['ids']);
				}
				if (empty($ids)) {
					echo CJSON::encode(array('status' => 'failure', 'msg' => 'No se recibieron datos para incluir.'));
					die();
				}
				$successCount = $failureCount = 0;
				foreach ($ids as $id) {
					$nombreComun = PctesaurosCe::model()->findByPk($id);
					$nuevoNombreComun= new PctesaurosCe();
					$nuevoNombreComun->catalogoespecies_id=$_POST['idCatalogo'];
					$nuevoNombreComun->tesauronombre=$nombreComun->tesauronombre;
					$nuevoNombreComun->grupohumano=$nombreComun->grupohumano;
					$nuevoNombreComun->idioma=$nombreComun->idioma;
					$nuevoNombreComun->regionesgeograficas=$nombreComun->regionesgeograficas;
					$nuevoNombreComun->paginaweb=$nombreComun->paginaweb;
					$nuevoNombreComun->tesaurocompleto=$nombreComun->tesaurocompleto;
					
					$transaction = Yii::app()->db->beginTransaction();
					
					try {
						$nuevoNombreComun->validate();
						yii::log(var_dump($nuevoNombreComun->getErrors()));
						yii::log($nuevoNombreComun->getErrors());
						($nuevoNombreComun->save(false) == true) ? $successCount++ : $failureCount++;
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollback();
						Yii::log("Error occurred while saving catalog species data. Rolling back... . Failure reason as reported in exception: " . $e->getMessage(), 'error');
					}
				}
				echo CJSON::encode(array('status' => 'success',
						'data' => array(
								'successCount' => $successCount,
								'failureCount' => $failureCount,
						)));
				die();
			} else {
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			}
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

		if(isset($_POST['PctesaurosCe']))
		{
			$model->attributes=$_POST['PctesaurosCe'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_tesauros));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('PctesaurosCe');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PctesaurosCe('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PctesaurosCe']))
			$model->attributes=$_GET['PctesaurosCe'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PctesaurosCe the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PctesaurosCe::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PctesaurosCe $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pctesauros-ce-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
