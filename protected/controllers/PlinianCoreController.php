<?php

class PlinianCoreController extends Controller
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
				'actions'=>array('excelfullexport'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('excelfullexport'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','excelfullexport'),
				//'users'=>array('amsuarez'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionExcelfullexport() {
		// get a reference to the path of PHPExcel classes 
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');

		// Turn off our amazing library autoload 
		spl_autoload_unregister(array('YiiBase','autoload'));

		// making use of our reference, include the main class
		// when we do this, phpExcel has its own autoload registration
		// procedure (PHPExcel_Autoloader::Register();)
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Once we have finished using the library, give back the
		// power to Yii...
		spl_autoload_register(array('YiiBase','autoload'));

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Catalogo de la Biodiversidad")
			->setLastModifiedBy("Catalogo de la Biodiversidad")
			->setTitle("Catalogo de la Biodiversidad - Reporte en formato Plinian Core")
			->setSubject("Catalogo de la Biodiversidad - Reporte en formato Plinian Core")
			->setDescription("Listado de fichas en formato Plinian Core.")
			->setKeywords("catalogo biodiversidad sib colombia pliniancore")
			->setCategory("Biodiversity file");

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'scientificname')
            ->setCellValue('B1', 'institutioncode')
            ->setCellValue('C1', 'taxonrecordid')
            ->setCellValue('D1', 'language')
            ->setCellValue('E1', 'creators')
            ->setCellValue('F1', 'distribution')
            ->setCellValue('G1', 'abstract')
            ->setCellValue('H1', 'kingdom')
            ->setCellValue('I1', 'phylum')
            ->setCellValue('J1', 'class')
            ->setCellValue('K1', 'order')
            ->setCellValue('L1', 'family')
            ->setCellValue('M1', 'genus')
            ->setCellValue('N1', 'synonyms')
            ->setCellValue('O1', 'authoryearofscientificname')
            ->setCellValue('P1', 'speciespublicationreference')
            ->setCellValue('Q1', 'commonnames')
            ->setCellValue('R1', 'typification')
            ->setCellValue('S1', 'globaluniqueidentifier')
            ->setCellValue('T1', 'contributors')
            ->setCellValue('U1', 'habit')
            ->setCellValue('V1', 'lifecycle')
            ->setCellValue('W1', 'reproduction')
            ->setCellValue('X1', 'annualcycle')
            ->setCellValue('Y1', 'scientificdescription')
            ->setCellValue('Z1', 'briefdescription')
            ->setCellValue('AA1', 'feeding')
            ->setCellValue('AB1', 'behavior')
            ->setCellValue('AC1', 'interactions')
            ->setCellValue('AD1', 'chromosomicnumbern')
            ->setCellValue('AE1', 'moleculardata')
            ->setCellValue('AF1', 'populationbiology')
            ->setCellValue('AG1', 'threatstatus')
            ->setCellValue('AH1', 'legislation')
            ->setCellValue('AI1', 'habitat')
            ->setCellValue('AJ1', 'territory')
            ->setCellValue('AK1', 'endemicity')
            ->setCellValue('AL1', 'uses')
            ->setCellValue('AM1', 'management')
            ->setCellValue('AN1', 'folklore')
            ->setCellValue('AO1', 'references1')
            ->setCellValue('AP1', 'unstructureddocumentation')
            ->setCellValue('AQ1', 'otherinformationsources')
            ->setCellValue('AR1', 'papers')
            ->setCellValue('AS1', 'identificationkeys')
            ->setCellValue('AT1', 'migratorydata')
            ->setCellValue('AU1', 'ecologicalsignificance')
            ->setCellValue('AV1', 'unstructurednaturalhistory')
            ->setCellValue('AW1', 'invasivenessdata')
            ->setCellValue('AX1', 'targetaudiences')
            ->setCellValue('AY1', 'version')
            ->setCellValue('AZ1', 'urlimage1')
            ->setCellValue('BA1', 'captionimage1')
            ->setCellValue('BB1', 'urlimage2')
            ->setCellValue('BC1', 'urlimage3')
            ->setCellValue('BD1', 'captionimage2')
            ->setCellValue('BE1', 'captionimage3')
            ->setCellValue('BF1', 'datelastmodified')
            ->setCellValue('BG1', 'datecreated')
            ->setCellValue('BH1', 'estado')
            ->setCellValue('BI1', 'contacto')
            ->setCellValue('BJ1', 'contactoresponsable')
            ->setCellValue('BK1', 'urlficha');

       	// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Fichas atributos plinian core');

        $condition= new CDbCriteria();
		$condition->order = "catalogoespecies_id ASC";
        $models = Catalogoespecies::model()->findAll($condition);
        $fila = 2;
        foreach($models as $model) {
        	if(isset($model->pcaatCe)) {
        		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$fila, strip_tags($model->pcaatCe->taxonnombre));
        		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$fila, $model->pcaatCe->autor);
        		$taxon = $model->pcaatCe->taxoncompleto;

        		$regex_pattern = '/Reino ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$fila, trim($match[1]));

				$regex_pattern = '/Phylum ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$fila, trim($match[1]));

				$regex_pattern = '/División ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$fila, trim($match[1]));

				$regex_pattern = '/Clase ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$fila, trim($match[1]));

				$regex_pattern = '/Orden ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$fila, trim($match[1]));

				$regex_pattern = '/Familia ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$fila, trim($match[1]));

				$regex_pattern = '/Género ([^>>]+)/';
				preg_match($regex_pattern, $taxon, $match);
				if(isset($match[1]))
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$fila, trim($match[1]));
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$fila, 'Sistema de Información sobre Biodiversidad de Colombia (SiB)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$fila, $model->catalogoespecies_id);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$fila, 'ES');

			if(isset($model->citacion)) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$fila, ($model->citacion->autor!=""?"Autor: ":"").$model->citacion->autor);
			}
			if(isset($model->ceAtributovalors)) {
				$atributos=$model->ceAtributovalors;
				$distribucionGeograficaColombia="";
				$distribucionGeograficaMundo="";
				$autores="";
				$textoPreAutores="Autor(es): ";
				$separadorAutores="";
				$colaboradores="";
				$textoPreColaboradores="Colaborado(es): ";
				$separadorColaboradores="";
				$editores="";
				$textoPreEditores="Editor(es): ";
				$separadorEditores="";
				$creditosEspecificos="";
				$revisores="";
				$textoPreRevisores="Revisor(es): ";
				$separadorRevisores="";
				$textoPreCreditosEspecificos="Créditos específicos: ";
				$separadorCreditosEspecificos="";
				$habitos="";
				$separadorHabitos="";
				$reproducciones="";
				$separadorReproducciones="";
				$descripcionesCientificas="";
				$separadorDescripcionesCientificas="";
				$alimentaciones="";
				$separadorAlimentaciones="";
				$comportamientos="";
				$separadorComportamientos="";
				$estadosPoblacion="";
				$separadorEstadosPoblacion="";
				$estadoAmenazaColombia="";
				$estadoAmenazaMundo="";
				$habitats="";
				$separadorHabitats="";
				$informacionUsos="";
				$separadorInformacionUsos="";
				$medidasConservacion="";
				$separadorMedidasConservacion="";
				$citaciones="";
				$separadorCitaciones="";
				$otrosRecursos="";
				$separadorOtrosRecursos="";
				$clavesTaxonomicas="";
				$separadorClavesTaxonomicas="";
				$ecologias="";
				$separadorEcologias="";
				$origenes="";
				$textoPreOrigenes="Origen: ";
				$separadorOrigenes="";
				$descripcionesInvasion="";
				$textoPreDescripcionesInvasion="Descripción de la invasión: ";
				$separadorDescripcionesInvasion="";
				$impactos="";
				$textoPreImpactos="Impactos: ";
				$separadorImpactos="";
				$mecanismosDeControl="";
				$textoPreMecanismosDeControl="Mecanismos de control: ";
				$separadorMecanismosDeControl="";
				$imagenFila="AZ";
				foreach($atributos as $atributo) {
					if(isset($atributo->atributo)) {
						if($atributo->atributo->nombre == "Distribución geográfica en Colombia") {
							$distribucionGeograficaColombia="Distribución geográfica en Colombia: ".$atributo->valor0->valor;
						}
						if($atributo->atributo->nombre == "Distribución geográfica en el mundo") {
							$distribucionGeograficaMundo="Distribución geográfica en el mundo: ".$atributo->valor0->valor;
						}
						if($atributo->atributo->nombre == "Descripción general") {
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$fila, $atributo->valor0->valor);
						}
						if($atributo->atributo->nombre == "Sinónimos") {
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$fila, $atributo->valor0->valor);	
						}
						if($atributo->atributo->nombre == "Información de tipos") {
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$fila, $atributo->valor0->valor);	
						}
						if($atributo->atributo->nombre == "Autor(es)") {
							$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
							$autores=$textoPreAutores.$autores.$separadorAutores.$contacto->persona;
							$textoPreAutores="";
							$separadorAutores=", ";
						}
						if($atributo->atributo->nombre == "Editor(es)") {
							$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
							$editores=$textoPreEditores.$editores.$separadorEditores.$contacto->persona;
							$textoPreEditores="";
							$separadorEditores=", ";
						}
						if($atributo->atributo->nombre == "Revisor(es)") {
							$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
							$revisores=$textoPreRevisores.$revisores.$separadorRevisores.$contacto->persona;
							$textoPreRevisores="";
							$separadorRevisores=", ";
						}
						if($atributo->atributo->nombre == "Colaborador(es)") {
							$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
							$colaboradores=$textoPreColaboradores.$colaboradores.$separadorColaboradores.$contacto->persona;
							$textoPreColaboradores="";
							$separadorColaboradores=", ";
						}
						if($atributo->atributo->nombre == "Créditos específicos") {
							$creditosEspecificos=$textoPreCreditosEspecificos.$creditosEspecificos.$separadorCreditosEspecificos.$atributo->valor0->valor;
							$textoPreCreditosEspecificos="";
							$separadorCreditosEspecificos=", ";
						}
						if($atributo->atributo->nombre == "Hábito") {
							$habitos=$habitos.$separadorHabitos.$atributo->valor0->valor;
							$separadorHabitos=", ";
						}
						if($atributo->atributo->nombre == "Reproducción") {
							$reproducciones=$reproducciones.$separadorReproducciones.$atributo->valor0->valor;
							$separadorReproducciones=", ";
						}
						if($atributo->atributo->nombre == "Descripción taxonómica") {
							$descripcionesCientificas=$descripcionesCientificas.$separadorDescripcionesCientificas.$atributo->valor0->valor;
							$separadorDescripcionesCientificas=", ";
						}
						if($atributo->atributo->nombre == "Alimentación") {
							$alimentaciones=$alimentaciones.$separadorAlimentaciones.$atributo->valor0->valor;
							$separadorAlimentaciones=", ";
						}
						if($atributo->atributo->nombre == "Comportamiento") {
							$comportamientos=$comportamientos.$separadorComportamientos.$atributo->valor0->valor;
							$separadorComportamientos=", ";
						}
						if($atributo->atributo->nombre == "Estado actual de la población") {
							$estadosPoblacion=$estadosPoblacion.$separadorEstadosPoblacion.$atributo->valor0->valor;
							$separadorEstadosPoblacion=", ";
						}
						if($atributo->etiqueta == 3) {
							$estadoAmenazaColombia="En Colombia: ".$atributo->valor0->valor;
						}
						if($atributo->etiqueta == 4) {
							$estadoAmenazaMundo="En el mundo: ".$atributo->valor0->valor;
						}
						if($atributo->atributo->nombre == "Hábitat") {
							$habitats=$habitats.$separadorHabitats.$atributo->valor0->valor;
							$separadorHabitats=", ";
						}
						if($atributo->atributo->nombre == "Información de usos") {
							$informacionUsos=$informacionUsos.$separadorInformacionUsos.$atributo->valor0->valor;
							$separadorInformacionUsos=", ";
						}
						if($atributo->atributo->nombre == "Medidas de conservación") {
							$medidasConservacion=$medidasConservacion.$separadorMedidasConservacion.$atributo->valor0->valor;
							$separadorMedidasConservacion=", ";
						}
						if($atributo->atributo->nombre == "Referencias bibliográficas") {
							$citacion=Citacion::model()->findByPk($atributo->valor0->valor);
							$citaciones=$citaciones.$separadorCitaciones.$citacion->autor.', '.$citacion->fecha.', '.$citacion->documento_titulo.', '.$citacion->editor.', '.$citacion->publicador.', '.$citacion->editorial.', '.$citacion->lugar_publicacion.', '.$citacion->hipervinculo;
							$separadorCitaciones=". ";
						}
						if($atributo->atributo->nombre == "Otros recursos en Internet") {
							$otrosRecursos=$otrosRecursos.$separadorOtrosRecursos.$atributo->valor0->valor;
							$separadorOtrosRecursos=", ";
						}
						if($atributo->atributo->nombre == "Claves taxonómicas") {
							$clavesTaxonomicas=$clavesTaxonomicas.$separadorClavesTaxonomicas.$atributo->valor0->valor;
							$separadorClavesTaxonomicas=", ";
						}
						if($atributo->atributo->nombre == "Ecología") {
							$ecologias=$ecologias.$separadorEcologias.$atributo->valor0->valor;
							$separadorEcologias=", ";
						}
						if($atributo->atributo->nombre == "Origen") {
							$origenes=$textoPreOrigenes.$origenes.$separadorOrigenes.$atributo->valor0->valor;
							$textoPreOrigenes="";
							$separadorOrigenes=", ";
						}
						if($atributo->atributo->nombre == "Descripción de la invasión") {
							$descripcionesInvasion=$textoPreDescripcionesInvasion.$descripcionesInvasion.$separadorDescripcionesInvasion.$atributo->valor0->valor;
							$textoPreDescripcionesInvasion="";
							$separadorDescripcionesInvasion=", ";
						}
						if($atributo->atributo->nombre == "Impactos") {
							$impactos=$textoPreImpactos.$impactos.$separadorImpactos.$atributo->valor0->valor;
							$textoPreImpactos="";
							$separadorImpactos=", ";
						}
						if($atributo->atributo->nombre == "Mecanismos de control") {
							$mecanismosDeControl=$textoPreMecanismosDeControl.$mecanismosDeControl.$separadorMecanismosDeControl.$atributo->valor0->valor;
							$textoPreMecanismosDeControl="";
							$separadorMecanismosDeControl=", ";
						}
						if($atributo->atributo->nombre == "Imagen") {
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue($imagenFila.$fila, $atributo->valor0->valor);
							if($imagenFila == "AZ") {
								$imagenFila="BB";
							} else if($imagenFila == "BB") {
								$imagenFila="BC";
							}
						}
					}
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$fila, $distribucionGeograficaColombia.($distribucionGeograficaColombia!=""?" ":"").$distribucionGeograficaMundo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$fila, $autores.($autores!=""?". ":"").$editores.($editores!=""?". ":"").$revisores.($revisores!=""?". ":"").$creditosEspecificos);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$fila, $habitos);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$fila, $reproducciones);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$fila, $descripcionesCientificas);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$fila, $alimentaciones);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$fila, $comportamientos);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$fila, $estadosPoblacion);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$fila, $estadoAmenazaColombia.($estadoAmenazaColombia!=""?". ":"").$estadoAmenazaMundo);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$fila, $habitats);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$fila, $informacionUsos);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$fila, $medidasConservacion);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$fila, $citaciones);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$fila, $otrosRecursos);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$fila, $clavesTaxonomicas);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$fila, $ecologias);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$fila, $origenes.($origenes!=""?" ":"").$descripcionesInvasion.($descripcionesInvasion!=""?" ":"").$impactos.($impactos!=""?" ":"").$mecanismosDeControl);
			}
			if(isset($model->pctesaurosCes)) {
				$nombresComunes=$model->pctesaurosCes;
				$contenido="";
				$separador="";
				foreach($nombresComunes as $nombreComun) {
					$contenido=$contenido.$separador.$nombreComun->tesauronombre;
					$separador=", ";
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$fila, $contenido);
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$fila, $model->catalogoespecies_id);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$fila, "Público en general");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY'.$fila, $model->fechaelaboracion);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BF'.$fila, $model->fechaactualizacion);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BG'.$fila, $model->fechaelaboracion);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BK'.$fila, 'http://www.siac.net.co/sib/catalogoespecies/especie.do?idBuscar='.$model->catalogoespecies_id.'&method=displayAAT');
			if(isset($model->verificacionce)) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BH'.$fila, $model->verificacionce->estado->nombre);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BI'.$fila, $model->verificacionce->contacto_id);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BJ'.$fila, $model->verificacionce->contactoresponsable_id);
			}
			$fila++;
        }

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Excel 2007 a 2013 Export
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Catalogo_Biodiversidad_Plinian_Core.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        // Excel 97/2003 EXPORT
		/*header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Catalogo_Biodiversidad_Plinian_Core.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');*/

		// PDF EXPORT
		/*header('Content-Type: application/pdf');
		header('Content-Disposition: attachment;filename="Catalogo_Biodiversidad_Plinian_Core.pdf"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'PDF'); */

		$objWriter->save('php://output');
		Yii::app()->end();

	}

}
?>