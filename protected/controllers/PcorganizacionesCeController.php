<?php
class PcorganizacionesCeController extends Controller
{
	public $layout='//layouts/column2';
	
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('PcorganizacionesCe');
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
	    $model=new PcorganizacionesCe;

	    if(isset($_POST['ajax']) && $_POST['ajax']==='client-account-create-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }

	    if(isset($_POST['PcorganizacionesCe']))
	    {
	        $model->attributes=$_POST['PcorganizacionesCe'];
	        if($model->validate())
	        {
				$this->saveModel($model);
				$this->redirect(array('view','catalogoespecies_id'=>$model->catalogoespecies_id, 'id_organizacion'=>$model->id_organizacion));
	        }
	    }
	    $this->render('create',array('model'=>$model));
	} 
	
	public function actionDelete($catalogoespecies_id, $id_organizacion)
	{
		if(Yii::app()->request->isPostRequest)
		{
			try
			{
				// we only allow deletion via POST request
				$this->loadModel($catalogoespecies_id, $id_organizacion)->delete();
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
	
	public function actionUpdate($catalogoespecies_id, $id_organizacion)
	{
		$model=$this->loadModel($catalogoespecies_id, $id_organizacion);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PcorganizacionesCe']))
		{
			$model->attributes=$_POST['PcorganizacionesCe'];
			$this->saveModel($model);
			$this->redirect(array('view',
	                    'catalogoespecies_id'=>$model->catalogoespecies_id, 'id_organizacion'=>$model->id_organizacion));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionAdmin()
	{
		$model=new PcorganizacionesCe('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PcorganizacionesCe']))
			$model->attributes=$_GET['PcorganizacionesCe'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionView($catalogoespecies_id, $id_organizacion)
	{		
		$model=$this->loadModel($catalogoespecies_id, $id_organizacion);
		$this->render('view',array('model'=>$model));
	}
	
	public function loadModel($catalogoespecies_id, $id_organizacion)
	{
		$model=PcorganizacionesCe::model()->findByPk(array('catalogoespecies_id'=>$catalogoespecies_id, 'id_organizacion'=>$id_organizacion));
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
					$organizacion = Organizaciones::model()->findByPk($id);
					$nuevaOrganizacion= new PcorganizacionesCe();
					$nuevaOrganizacion->catalogoespecies_id=$_POST['idCatalogo'];
					$nuevaOrganizacion->id_organizacion=$organizacion->id_organizacion;
					$nuevaOrganizacion->organizaciones=$organizacion->organizacion;
	
					$transaction = Yii::app()->db->beginTransaction();
	
					try {
						$nuevaOrganizacion->validate();
						yii::log(var_dump($nuevaOrganizacion->getErrors()));
						yii::log($nuevaOrganizacion->getErrors());
						($nuevaOrganizacion->save(false) == true) ? $successCount++ : $failureCount++;
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