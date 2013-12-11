<?php

class AtributovalorController extends Controller
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
				'actions'=>array('admin','delete'),
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
		$atributo_Valor=new Atributovalor;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if (Yii::app()->request->isAjaxRequest) {
			if(isset($_POST['labelAttribute']) && isset($_POST['idCatalog']))
			{
				if ($_POST['labelAttribute'] == 2) {
					// It's Estado de amenaza según categorías UICN attribute
					if($_POST['colombia'] == "true") {
						if(count(CeAtributovalor::model()->findByAttributes(array('valor'=>3,'etiqueta'=>2,'catalogoespecies_id'=>$_POST['idCatalog']))) == 0) {
							$ce_AtributoValor = new CeAtributovalor();
							$ce_AtributoValor->valor=3;
							$ce_AtributoValor->etiqueta=2;
							$ce_AtributoValor->catalogoespecies_id=$_POST['idCatalog'];
							$ce_AtributoValor->save();
						}
						foreach($_POST['selectedColombia'] as $selectedColombia) {
							if(count(CeAtributovalor::model()->findByAttributes(array('valor'=>$selectedColombia,'etiqueta'=>3,'catalogoespecies_id'=>$_POST['idCatalog']))) == 0) {
								$ce_AtributoValor = new CeAtributovalor();
								$ce_AtributoValor->valor=$selectedColombia;
								$ce_AtributoValor->etiqueta=3;
								$ce_AtributoValor->catalogoespecies_id=$_POST['idCatalog'];
								$ce_AtributoValor->save();
							}
						}
					}
					if($_POST['mundo'] == "true") {
						if(count(CeAtributovalor::model()->findByAttributes(array('valor'=>4,'etiqueta'=>2,'catalogoespecies_id'=>$_POST['idCatalog']))) == 0) {
							$ce_AtributoValor = new CeAtributovalor();
							$ce_AtributoValor->valor=4;
							$ce_AtributoValor->etiqueta=2;
							$ce_AtributoValor->catalogoespecies_id=$_POST['idCatalog'];
							$ce_AtributoValor->save();
						}
						foreach($_POST['selectedMundo'] as $selectedMundo) {
							if(count(CeAtributovalor::model()->findByAttributes(array('valor'=>$selectedMundo,'etiqueta'=>4,'catalogoespecies_id'=>$_POST['idCatalog']))) == 0) {
								$ce_AtributoValor = new CeAtributovalor();
								$ce_AtributoValor->valor=$selectedMundo;
								$ce_AtributoValor->etiqueta=4;
								$ce_AtributoValor->catalogoespecies_id=$_POST['idCatalog'];
								$ce_AtributoValor->save();
							}
						}
					}
					if(!isset($ce_AtributoValor)) {
						echo CJSON::encode(array(
							'status'=>'failure',
							'respuesta'=>"<p>No se actualizaron atributos porque los datos ya existen.</p>",
						));
						exit;
					}
				} else {
					$atributo_Valor->atributotipo_id = 4;
					$atributo_Valor->valor = $_POST['value'];
					$atributo_Valor->save();
					$ce_AtributoValor = new CeAtributovalor();
					$ce_AtributoValor->valor=$atributo_Valor->id;
					$ce_AtributoValor->etiqueta=$_POST['labelAttribute'];
					$ce_AtributoValor->catalogoespecies_id=$_POST['idCatalog'];
				}
				if($ce_AtributoValor->save()) {
					$model=Catalogoespecies::model()->findByPk($_POST['idCatalog']);
					$atributos["Distribución altitudinal"]=array(); // 1
					$atributos["Estado de amenaza según categorías UICN"]=array(); // 2
					$atributos["Estado de amenaza según categorías UICN"]["En Colombia"]=array(); // 3
					$atributos["Estado de amenaza según categorías UICN"]["En el mundo"]=array(); // 4
					$atributos["Estado CITES"]=array(); // 5
					$atributos["Factores de amenaza"]=array(); // 6
					$atributos["Estado actual de la población"]=array(); // 7
					$atributos["Distribución geográfica en Colombia"]=array(); // 8
					$atributos["Ecosistema"]=array(); // 10
					$atributos["Región natural"]=array(); // 11
					$atributos["Descripción taxonómica"]=array(); // 12
					$atributos["Información de usos"]=array(); // 14
					$atributos["Información de alerta"]=array(); // 15
					$atributos["Medidas de conservación"]=array(); // 16
					$atributos["Ecología"]=array(); // 17
					$atributos["Otros recursos en Internet"]=array(); // 18
					$atributos["Autor(es)"]=array(); // 19
					$atributos["Editor(es)"]=array(); // 20
					$atributos["Recursos multimedia"]=array(); // 21
					$atributos["Alimentación"]=array(); // 22
					$atributos["Colaborador(es)"]=array(); // 24
					$atributos["Revisor(es)"]=array(); // 25
					$atributos["Comportamiento"]=array(); // 26
					$atributos["Claves taxonómicas"]=array(); // 27
					$atributos["Referencias bibliográficas"]=array(); // 28
					$atributos["Etimología del nombre científico"]=array(); // 30
					$atributos["Hábitat"]=array(); // 31
					$atributos["Vocalizaciones"]=array(); // 32
					$atributos["Reproducción"]=array(); // 35
					$atributos["Descripción general"]=array(); // 36
					$atributos["Imagen"]=array(); // 39
					$atributos["Mapa"]=array(); // 40
					$atributos["Video"]=array(); // 41
					$atributos["Sonido"]=array(); // 42
					$atributos["Créditos específicos"]=array(); // 436
					$atributos["Descripción de la invasión"]=array(); // 3529
					$atributos["Distribución geográfica en el mundo"]=array(); // 148
					$atributos["Hábito"]=array(); // 903
					$atributos["Impactos"]=array(); // 3530
					$atributos["Información de tipos"]=array(); // 150
					$atributos["Invasora"]=array(); // 6784
					$atributos["Mecanismos de control"]=array(); // 3531
					$atributos["Metadatos"]=array(); // 437
					$atributos["Origen"]=array(); // 904
					$atributos["Registros biológicos"]=array(); // 149
					$atributos["Sinónimos"]=array(); // 32210
					foreach($model->ceAtributovalors as $ceAtributoValor) {
						if($ceAtributoValor->etiqueta == "1") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Distribución altitudinal"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "2") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Estado de amenaza según categorías UICN"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "3") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Estado de amenaza según categorías UICN"]["En Colombia"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "4") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Estado de amenaza según categorías UICN"]["En el mundo"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "5") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Estado CITES"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "6") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Factores de amenaza"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "7") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Estado actual de la población"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "8") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Distribución geográfica en Colombia"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "10") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Ecosistema"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "11") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Región natural"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "12") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Descripción taxonómica"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "14") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Información de usos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "15") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Información de alerta"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "16") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Medidas de conservación"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "17") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Ecología"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "18") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Otros recursos en Internet"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "19") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$autor=Contactos::model()->findByPk($atributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Autor(es)"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$autor->persona.', '.$autor->organizacion.', '.$autor->correo_electronico.', '.$autor->direccion));
						} else if($ceAtributoValor->etiqueta == "20") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$autor=Contactos::model()->findByPk($atributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Editor(es)"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$autor->persona.', '.$autor->organizacion.', '.$autor->correo_electronico.', '.$autor->direccion));
						} else if($ceAtributoValor->etiqueta == "21") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Recursos multimedia"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "22") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Alimentación"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "24") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$autor=Contactos::model()->findByPk($atributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Colaborador(es)"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$autor->persona.', '.$autor->organizacion.', '.$autor->correo_electronico.', '.$autor->direccion));
						} else if($ceAtributoValor->etiqueta == "25") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$autor=Contactos::model()->findByPk($atributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Revisor(es)"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$autor->persona.', '.$autor->organizacion.', '.$autor->correo_electronico.', '.$autor->direccion));
						} else if($ceAtributoValor->etiqueta == "26") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Comportamiento"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "27") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Claves taxonómicas"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "28") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$citacion=Citacion::model()->findByPk($atributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Referencias bibliográficas"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$citacion->documento_titulo.', '.$citacion->autor.', '.$citacion->editor.', '.$citacion->publicador.', '.$citacion->editorial.', '.$citacion->lugar_publicacion.', '.$citacion->hipervinculo));
						} else if($ceAtributoValor->etiqueta == "30") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Etimología del nombre científico"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "31") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Hábitat"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "32") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Vocalizaciones"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "35") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Reproducción"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "36") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Descripción general"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "39") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Imagen"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor, 'nombreCientifico'=>$model->pcaatCe->taxonnombre, 'autor'=>$model->pcaatCe->autor));
						} else if($ceAtributoValor->etiqueta == "40") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Mapa"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "41") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Video"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "42") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Sonido"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "436") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Créditos específicos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "3529") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Descripción de la invasión"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "148") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Distribución geográfica en el mundo"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "903") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Hábito"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "3530") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Impactos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "150") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Información de tipos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "6784") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Invasora"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "3531") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Mecanismos de control"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "437") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Metadatos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "904") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Origen"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "149") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Registros biológicos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						} else if($ceAtributoValor->etiqueta == "32210") {
							$atributoValor=Atributovalor::model()->findByPk($ceAtributoValor->valor);
							$etiquetaValor=Atributovalor::model()->findByPk($ceAtributoValor->etiqueta);
							array_push($atributos["Sinónimos"], array('ceatributovalor_id'=>$ceAtributoValor->ceatributovalor_id, 'etiqueta'=>$ceAtributoValor->etiqueta, 'valor'=>$ceAtributoValor->valor, 'etiquetaValor'=>$etiquetaValor->valor, 'contenido'=>$atributoValor->valor));
						}
					}
					if(isset($_POST['value'])) {
						$updatedValue = $_POST['value'];
					} else {
						$updatedValue = "";
					}
					echo CJSON::encode(array(
						'status'=>'success',
						'respuesta'=>"<p>Atributo correspondiente a:<br><strong>".$_POST['attributeName']."</strong></p><p>Con valor:<br>".$updatedValue."</p><p>Ha sido guardado exitosamente.</p>",
						'newAttributeList'=>$this->renderPartial('//catalogo/_atributos_listado', array('atributos'=>$atributos, 'model'=>$model), true),
					));
					exit;
				} else {
					if(isset($_POST['value'])) {
						$updatedValue = $_POST['value'];
					} else {
						$updatedValue = "";
					}
					echo CJSON::encode(array(
						'status'=>'failure',
						'respuesta'=>"<p>Fallo al guardar el atributo correspondiente a:<br><strong>".$_POST['attributeName']."</strong></p><p>Con valor:<br>".$updatedValue,
					));
					exit;
				}
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

		if(isset($_POST['Atributovalor']))
		{
			$model->attributes=$_POST['Atributovalor'];
			
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest) {
					echo CJSON::encode(array(
						'status'=>'success',
						'respuesta'=>"Atributo guardado exitosamente.",
					));
					exit;
				} else {
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		
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
		$dataProvider=new CActiveDataProvider('Atributovalor');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Atributovalor('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Atributovalor']))
			$model->attributes=$_GET['Atributovalor'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Atributovalor the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Atributovalor::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Atributovalor $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='atributovalor-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
