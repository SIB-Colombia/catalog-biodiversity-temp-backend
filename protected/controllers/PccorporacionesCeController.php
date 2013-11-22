<?php
class PccorporacionesCeController extends Controller
{
	public $layout='//layouts/column2';
	
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('PccorporacionesCe');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
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
				'actions'=>array('admin','delete','include'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
	    $model=new PccorporacionesCe;

	    if(isset($_POST['ajax']) && $_POST['ajax']==='client-account-create-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }

	    if(isset($_POST['PccorporacionesCe']))
	    {
	        $model->attributes=$_POST['PccorporacionesCe'];
	        if($model->validate())
	        {
				$this->saveModel($model);
				$this->redirect(array('view','catalogoespecies_id'=>$model->catalogoespecies_id, 'id_corporacion'=>$model->id_corporacion));
	        }
	    }
	    $this->render('create',array('model'=>$model));
	} 
	
	public function actionDelete($catalogoespecies_id, $id_corporacion)
	{
		if(Yii::app()->request->isPostRequest)
		{
			try
			{
				// we only allow deletion via POST request
				$this->loadModel($catalogoespecies_id, $id_corporacion)->delete();
			}
			catch(Exception $e) 
			{
				$this->showError($e);
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionUpdate($catalogoespecies_id, $id_corporacion)
	{
		$model=$this->loadModel($catalogoespecies_id, $id_corporacion);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PccorporacionesCe']))
		{
			$model->attributes=$_POST['PccorporacionesCe'];
			$this->saveModel($model);
			$this->redirect(array('view',
	                    'catalogoespecies_id'=>$model->catalogoespecies_id, 'id_corporacion'=>$model->id_corporacion));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionAdmin()
	{
		$model=new PccorporacionesCe('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PccorporacionesCe']))
			$model->attributes=$_GET['PccorporacionesCe'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionView($catalogoespecies_id, $id_corporacion)
	{		
		$model=$this->loadModel($catalogoespecies_id, $id_corporacion);
		$this->render('view',array('model'=>$model));
	}
	
	public function loadModel($catalogoespecies_id, $id_corporacion)
	{
		$model=PccorporacionesCe::model()->findByPk(array('catalogoespecies_id'=>$catalogoespecies_id, 'id_corporacion'=>$id_corporacion));
		if($model==null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function saveModel($model)
	{
		try
		{
			$model->save();
		}
		catch(Exception $e)
		{
			$this->showError($e);
		}		
	}

	function showError(Exception $e)
	{
		if($e->getCode()==23000)
			$message = "This operation is not permitted due to an existing foreign key reference.";
		else
			$message = "Invalid operation.";
		throw new CHttpException($e->getCode(), $message);
	}
	
	public function actionInclude() {
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
					$corporacion = Corporaciones::model()->findByPk($id);
					$nuevaCorporacion= new PccorporacionesCe();
					$nuevaCorporacion->catalogoespecies_id=$_POST['idCatalogo'];
					$nuevaCorporacion->id_corporacion=$corporacion->id_corporacion;
					$nuevaCorporacion->coorporaciones=$corporacion->corporacion;
	
					$transaction = Yii::app()->db->beginTransaction();
	
					try {
						$nuevaCorporacion->validate();
						yii::log(var_dump($nuevaCorporacion->getErrors()));
						($nuevaCorporacion->save(false) == true) ? $successCount++ : $failureCount++;
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
}