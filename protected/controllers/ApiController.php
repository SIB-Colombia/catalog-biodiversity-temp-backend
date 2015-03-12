<?php
ob_start("ob_gzhandler");
class ApiController extends Controller
{
	// Members
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers
	 */
	Const APPLICATION_ID = 'CLIENTE98797876X23V1';

	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $format = 'json';
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array();
	}

	// Actions

	public function actionCarrusel() {
		// Get the respective model instance
		switch($_GET['model'])
		{
			case 'carrusel':
				if(isset($_GET['count'])) {
					$totalLimit = $_GET['count'];
				} else {
					$totalLimit = '1';
				}
				$fichasConImagen = Yii::app()->db->createCommand()
					->select('catalogoespecies.catalogoespecies_id')
					->from('public.catalogoespecies, public.ce_atributovalor, public.atributos')
					->where("catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = 'Imagen'")
					->group('catalogoespecies.catalogoespecies_id')
					->order('random()')
					->limit($totalLimit)
					->queryAll();
				if(is_null($fichasConImagen))
					$this->_sendResponse(404, 'No Items with image has been found with id '.$_GET['id']);
				$rows = array();
				foreach ($fichasConImagen as $value) {
					$model = Catalogoespecies::model()->findByPk($value["catalogoespecies_id"]);
					if(is_null($model)) {
						$this->_sendResponse(404, 'No Item found with id '.$value["catalogoespecies_id"]);
					} else {
						// Prepare response
						$rows[$model->catalogoespecies_id] = $model->attributes;
						if(isset($model->pcaatCe)) {
							$rows[$model->catalogoespecies_id]["info_taxonomica"] = $model->pcaatCe->attributes;
						}
						if(isset($model->pctesaurosCes)) {
							$nombresComunes=$model->pctesaurosCes;
							foreach($nombresComunes as $nombreComun) {
								$rows[$model->catalogoespecies_id]["nombres_comunes"][]=$nombreComun->attributes;
							}
						}
						if(isset($model->ceAtributovalors)) {
							$atributos=$model->ceAtributovalors;
							foreach($atributos as $atributo) {
								if(isset($atributo->atributo)) {
									if($atributo->atributo->nombre == "Imagen") {
										$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
									}
								}
							}
						}
						if(isset($rows[$model->catalogoespecies_id]["atributos"]["Imagen"])) {
							$counterArray=0;
							foreach ($rows[$model->catalogoespecies_id]["atributos"]["Imagen"] as $imagen) {
								$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
								$extension = end(explode('.', $imagen));
								$filename = current(explode('.', $imagen));
								if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
									mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
								}
								if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_140x140.'.$extension)) {
									$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_140x140.'.$extension, 140, 140, 1);
								}
								$rows[$model->catalogoespecies_id]["atributos"]["ImagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode($filename).'_140x140.'.$extension;
								$rows[$model->catalogoespecies_id]["atributos"]["Imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
								$counterArray++;
							}
						}
					}
				}
				$this->_sendResponse(200, CJSON::encode($rows));
				break;
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
				'Error: Mode <b>carrusel</b> is not implemented for model <b>%s</b>',
				$_GET['model']) );
				Yii::app()->end();
		}
	}

	public function actionListSpecies() {
		$query='SELECT "t"."catalogoespecies_id", "pcaatCe".taxonnombre FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") WHERE "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id)) ORDER BY "pcaatCe".taxonnombre ASC';
		$models = Catalogoespecies::model()->findAllBySql($query);
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, CJSON::encode('No items where found'));
		} else {
			// Prepare response
			$rows = array();
			$rows["data"] = [];
			$counter = 0;
			foreach($models as $model) {
				$rows["data"][$counter]["id"] = $model->catalogoespecies_id;
				$rows["data"][$counter]["taxon_nombre"] = ($model->pcaatCe->taxonnombre != "" ? $model->pcaatCe->taxonnombre : null);
				$counter++;
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionListPreviewWithImagesRandom() {
		$query='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") WHERE "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) ORDER BY random() limit 20';
		$models = Catalogoespecies::model()->findAllBySql($query);
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, CJSON::encode('No items where found'));
		} else {
			// Prepare response
			$rows = array();
			$rows["data"] = [];
			$counter = 0;
			foreach($models as $model) {
				$rows["data"][$counter]["id"] = $model->catalogoespecies_id;
				$rows["data"][$counter]["taxon_nombre"] = ($model->pcaatCe->taxonnombre != "" ? $model->pcaatCe->taxonnombre : null);
				$rows["data"][$counter]["autor"] = ($model->pcaatCe->autor != "" ? $model->pcaatCe->autor : null);
				if (preg_match('/Reino(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["reino"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Phylum(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["phylum"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Clase(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["clase"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Orden(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["orden"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Familia(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["familia"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Género(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(preg_match('/Genero(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Especie(.*)/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["especie"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(isset($model->pctesaurosCes)) {
					$nombresComunes=$model->pctesaurosCes;
					$rows["data"][$counter]["nombres_comunes"] = [];
					$counterNombreComun = 0;
					foreach($nombresComunes as $nombreComun) {
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["nombre"] = ($nombreComun->tesauronombre != "" ? $nombreComun->tesauronombre : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["idioma"] = ($nombreComun->idioma != "" ? $nombreComun->idioma : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["region_geografica"] = ($nombreComun->regionesgeograficas != "" ? $nombreComun->regionesgeograficas : null);
						$counterNombreComun++;
					}
				}
				if(isset($model->pcdepartamentosCes)) {
					$departamentos=$model->pcdepartamentosCes;
					$rows["data"][$counter]["departamentos"] = [];
					$counterDepartamento = 0;
					foreach($departamentos as $departamento) {
						$rows["data"][$counter]["departamentos"][$counterDepartamento]["departamento"] = ($departamento->departamento->departamento != "" ? $departamento->departamento->departamento : null);
						$counterDepartamento++;
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					$rows["data"][$counter]["imagenes"]["imagen"] = [];
					$counterImagen = 0;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->atributo->nombre == "Imagen") {
								$rows["data"][$counter]["imagenes"]["imagen"][$counterImagen] = $atributo->valor0->valor;
								$counterImagen++;
							}
						}
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
								$rows["data"][$counter]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
							}
						}
					}
				}
				if(isset($rows["data"][$counter]["imagenes"]["imagen"])) {
					$counterArray=0;
					foreach ($rows["data"][$counter]["imagenes"]["imagen"] as $imagen) {
						$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
						$extension = end(explode('.', $imagen));
						$filename = current(explode('.', $imagen));
						if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
							mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension, 140, 140, 1);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension, 270, 270, 1);
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_140x140.'.$extension;
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_270x270.'.$extension;
						}
						$rows["data"][$counter]["imagenes"]["imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
						$counterArray++;
					}
				}
				$counter++;
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionListPreviewWithImagesRandomParamo() {
		$query='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\'))  AND "public".humedales_y_paramos.tipo = \'Paramo\' ORDER BY random() limit 20';
		$models = Catalogoespecies::model()->findAllBySql($query);
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, CJSON::encode('No items where found'));
		} else {
			// Prepare response
			$rows = array();
			$rows["data"] = [];
			$counter = 0;
			foreach($models as $model) {
				$rows["data"][$counter]["id"] = $model->catalogoespecies_id;
				$rows["data"][$counter]["taxon_nombre"] = ($model->pcaatCe->taxonnombre != "" ? $model->pcaatCe->taxonnombre : null);
				$rows["data"][$counter]["autor"] = ($model->pcaatCe->autor != "" ? $model->pcaatCe->autor : null);
				if (preg_match('/Reino(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["reino"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Phylum(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["phylum"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Clase(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["clase"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Orden(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["orden"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Familia(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["familia"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Género(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(preg_match('/Genero(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Especie(.*)/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["especie"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(isset($model->pctesaurosCes)) {
					$nombresComunes=$model->pctesaurosCes;
					$rows["data"][$counter]["nombres_comunes"] = [];
					$counterNombreComun = 0;
					foreach($nombresComunes as $nombreComun) {
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["nombre"] = ($nombreComun->tesauronombre != "" ? $nombreComun->tesauronombre : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["idioma"] = ($nombreComun->idioma != "" ? $nombreComun->idioma : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["region_geografica"] = ($nombreComun->regionesgeograficas != "" ? $nombreComun->regionesgeograficas : null);
						$counterNombreComun++;
					}
				}
				if(isset($model->pcdepartamentosCes)) {
					$departamentos=$model->pcdepartamentosCes;
					$rows["data"][$counter]["departamentos"] = [];
					$counterDepartamento = 0;
					foreach($departamentos as $departamento) {
						$rows["data"][$counter]["departamentos"][$counterDepartamento]["departamento"] = ($departamento->departamento->departamento != "" ? $departamento->departamento->departamento : null);
						$counterDepartamento++;
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					$rows["data"][$counter]["imagenes"]["imagen"] = [];
					$counterImagen = 0;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->atributo->nombre == "Imagen") {
								$rows["data"][$counter]["imagenes"]["imagen"][$counterImagen] = $atributo->valor0->valor;
								$counterImagen++;
							}
						}
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
								$rows["data"][$counter]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
							}
						}
					}
				}
				if(isset($rows["data"][$counter]["imagenes"]["imagen"])) {
					$counterArray=0;
					foreach ($rows["data"][$counter]["imagenes"]["imagen"] as $imagen) {
						$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
						$extension = end(explode('.', $imagen));
						$filename = current(explode('.', $imagen));
						if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
							mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension, 140, 140, 1);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension, 270, 270, 1);
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_140x140.'.$extension;
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_270x270.'.$extension;
						}
						$rows["data"][$counter]["imagenes"]["imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
						$counterArray++;
					}
				}
				$counter++;
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionListPreviewWithImagesRandomHumedal() {
		$query='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\'))  AND "public".humedales_y_paramos.tipo = \'Humedal\' ORDER BY random() limit 20';
		$models = Catalogoespecies::model()->findAllBySql($query);
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, CJSON::encode('No items where found'));
		} else {
			// Prepare response
			$rows = array();
			$rows["data"] = [];
			$counter = 0;
			foreach($models as $model) {
				$rows["data"][$counter]["id"] = $model->catalogoespecies_id;
				$rows["data"][$counter]["taxon_nombre"] = ($model->pcaatCe->taxonnombre != "" ? $model->pcaatCe->taxonnombre : null);
				$rows["data"][$counter]["autor"] = ($model->pcaatCe->autor != "" ? $model->pcaatCe->autor : null);
				if (preg_match('/Reino(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["reino"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Phylum(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["phylum"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Clase(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["clase"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Orden(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["orden"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Familia(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["familia"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Género(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(preg_match('/Genero(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Especie(.*)/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows["data"][$counter]["especie"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(isset($model->pctesaurosCes)) {
					$nombresComunes=$model->pctesaurosCes;
					$rows["data"][$counter]["nombres_comunes"] = [];
					$counterNombreComun = 0;
					foreach($nombresComunes as $nombreComun) {
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["nombre"] = ($nombreComun->tesauronombre != "" ? $nombreComun->tesauronombre : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["idioma"] = ($nombreComun->idioma != "" ? $nombreComun->idioma : null);
						$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["region_geografica"] = ($nombreComun->regionesgeograficas != "" ? $nombreComun->regionesgeograficas : null);
						$counterNombreComun++;
					}
				}
				if(isset($model->pcdepartamentosCes)) {
					$departamentos=$model->pcdepartamentosCes;
					$rows["data"][$counter]["departamentos"] = [];
					$counterDepartamento = 0;
					foreach($departamentos as $departamento) {
						$rows["data"][$counter]["departamentos"][$counterDepartamento]["departamento"] = ($departamento->departamento->departamento != "" ? $departamento->departamento->departamento : null);
						$counterDepartamento++;
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					$rows["data"][$counter]["imagenes"]["imagen"] = [];
					$counterImagen = 0;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->atributo->nombre == "Imagen") {
								$rows["data"][$counter]["imagenes"]["imagen"][$counterImagen] = $atributo->valor0->valor;
								$counterImagen++;
							}
						}
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
								$rows["data"][$counter]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
							}
						}
					}
				}
				if(isset($rows["data"][$counter]["imagenes"]["imagen"])) {
					$counterArray=0;
					foreach ($rows["data"][$counter]["imagenes"]["imagen"] as $imagen) {
						$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
						$extension = end(explode('.', $imagen));
						$filename = current(explode('.', $imagen));
						if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
							mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension, 140, 140, 1);
						}
						if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension, 270, 270, 1);
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_140x140.'.$extension;
						}
						if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
							$rows["data"][$counter]["imagenes"]["imagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_270x270.'.$extension;
						}
						$rows["data"][$counter]["imagenes"]["imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
						$counterArray++;
					}
				}
				$counter++;
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionList()
	{
		// Get the respective model instance
		switch($_GET['model'])
		{
			case 'fichas':
			case 'fichasresumen':
				if(isset($_GET['page'])) {
					$offset = ($_GET['page'] - 1) * 20;
					if(isset($_GET['priorityimages'])) {
						$queryWithImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) ';
						$queryWithoutImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id NOT IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) ';
						if(isset($_GET['scientificname'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
						}
						if(isset($_GET['taxon'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
						}
						if(isset($_GET['id'])) {
							$queryWithImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
						}
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$queryWithImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
						}
						if(isset($_GET['query'])) {
							$queryWithImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';
							$queryWithoutImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';							
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['query'])."%'";
							$queryWithImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
							$queryWithoutImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
						}
						$queryWithImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						$queryWithoutImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$queryWithImages .= 'ORDER BY "pcaatCe".taxonnombre ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".taxonnombre ';
							} else if($_GET['order'] == "author") {
								$queryWithImages .= 'ORDER BY "pcaatCe".autor ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".autor ';
							} else {
								$queryWithImages .= 'ORDER BY fechaelaboracion ';
								$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
							}
						} else {
							$queryWithImages .= 'ORDER BY fechaelaboracion ';
							$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$queryWithImages .= 'ASC';
								$queryWithoutImages .= 'ASC';
							} else {
								$queryWithImages .= 'DESC';
								$queryWithoutImages .= 'DESC';
							}
						} else {
							$queryWithImages .= 'DESC';
							$queryWithoutImages .= 'DESC';
						}
						$unionSQL = '('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.') LIMIT 20 OFFSET '.$offset;
						$unionSQLALL = 'select count(*) FROM (('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.')) AS totalRegs';
						$models = Catalogoespecies::model()->findAllBySql($unionSQL);
						$countreg = Catalogoespecies::model()->countBySql($unionSQLALL);
						//yii::log(CVarDumper::dumpAsString($models[0]->catalogoespecies_id), CLogger::LEVEL_INFO);
					} else {
						$condition= new CDbCriteria();
						$condition->join = 'INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->join .= 'INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->addCondition('"verificacionce".estado_id = 2');
						//$condition->with = array('pcaatCe', 'citacion', 'verificacionce', 'pctesaurosCes', 'pcdepartamentosCes', 'pcregionnaturalCes', 'pccorporacionesCes', 'pcorganizacionesCes', 'ceAtributovalors');
						if(isset($_GET['scientificname'])) {
							$condition->compare('LOWER("pcaatCe".taxonnombre)', strtolower($_GET['scientificname']), true );
						}
						if(isset($_GET['taxon'])) {
							$condition->compare('LOWER("pcaatCe".taxoncompleto)', strtolower($_GET['taxon']), true );
						}
						if(isset($_GET['id'])) {
							$condition->compare('t.catalogoespecies_id',$_GET['id']);
						}
						$sql='';
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')', 'OR');
						}
						if(isset($_GET['onlyimages'])) {
							if($_GET['onlyimages'] == "true") {
								$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
									."FROM public.catalogoespecies, public.ce_atributovalor, public.atributos "
									."WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = 'Imagen'";
								$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')');
							}
						}
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$condition->order = '"pcaatCe".taxonnombre';
							} else if($_GET['order'] == "author") {
								$condition->order = '"pcaatCe".autor';
							} else {
								$condition->order = "fechaelaboracion";
							}
						} else {
							$condition->order = "fechaelaboracion";
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$condition->order = $condition->order." ASC";
							} else {
								$condition->order = $condition->order." DESC";
							}
						} else {
							$condition->order = $condition->order." DESC";
						}
						$condition->limit=20;
						$condition->offset=$offset;
						$models = Catalogoespecies::model()->findAll($condition);
						$condition->limit=null;
						$condition->offset=null;
						$countreg = Catalogoespecies::model()->count($condition);
					}
					//$this->render('index');
					//Yii::app()->end();
				} else {
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Full list is not implemented for model <b>%s</b>', $_GET['model']) );
					Yii::app()->end();
				}
				break;
			case 'fichasresumenparamo':
				if(isset($_GET['page'])) {
					$offset = ($_GET['page'] - 1) * 20;
					if(isset($_GET['priorityimages'])) {
						$queryWithImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) AND "public".humedales_y_paramos.tipo = \'Paramo\' ';
						$queryWithoutImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id NOT IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) AND "public".humedales_y_paramos.tipo = \'Paramo\' ';
						if(isset($_GET['scientificname'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
						}
						if(isset($_GET['taxon'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
						}
						if(isset($_GET['id'])) {
							$queryWithImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
						}
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$queryWithImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
						}
						if(isset($_GET['query'])) {
							$queryWithImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';
							$queryWithoutImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';							
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['query'])."%'";
							$queryWithImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
							$queryWithoutImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
						}
						$queryWithImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						$queryWithoutImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$queryWithImages .= 'ORDER BY "pcaatCe".taxonnombre ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".taxonnombre ';
							} else if($_GET['order'] == "author") {
								$queryWithImages .= 'ORDER BY "pcaatCe".autor ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".autor ';
							} else {
								$queryWithImages .= 'ORDER BY fechaelaboracion ';
								$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
							}
						} else {
							$queryWithImages .= 'ORDER BY fechaelaboracion ';
							$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$queryWithImages .= 'ASC';
								$queryWithoutImages .= 'ASC';
							} else {
								$queryWithImages .= 'DESC';
								$queryWithoutImages .= 'DESC';
							}
						} else {
							$queryWithImages .= 'DESC';
							$queryWithoutImages .= 'DESC';
						}
						$unionSQL = '('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.') LIMIT 20 OFFSET '.$offset;
						$unionSQLALL = 'select count(*) FROM (('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.')) AS totalRegs';
						$models = Catalogoespecies::model()->findAllBySql($unionSQL);
						$countreg = Catalogoespecies::model()->countBySql($unionSQLALL);
						//yii::log(CVarDumper::dumpAsString($models[0]->catalogoespecies_id), CLogger::LEVEL_INFO);
					} else {
						$condition= new CDbCriteria();
						$condition->join = 'INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->join .= 'INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->join .= 'INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id';
						$condition->addCondition('"public".humedales_y_paramos.tipo = \'Paramo\'');
						$condition->addCondition('"verificacionce".estado_id = 2');
						//$condition->with = array('pcaatCe', 'citacion', 'verificacionce', 'pctesaurosCes', 'pcdepartamentosCes', 'pcregionnaturalCes', 'pccorporacionesCes', 'pcorganizacionesCes', 'ceAtributovalors');
						if(isset($_GET['scientificname'])) {
							$condition->compare('LOWER("pcaatCe".taxonnombre)', strtolower($_GET['scientificname']), true );
						}
						if(isset($_GET['taxon'])) {
							$condition->compare('LOWER("pcaatCe".taxoncompleto)', strtolower($_GET['taxon']), true );
						}
						if(isset($_GET['id'])) {
							$condition->compare('t.catalogoespecies_id',$_GET['id']);
						}
						$sql='';
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')', 'OR');
						}
						if(isset($_GET['onlyimages'])) {
							if($_GET['onlyimages'] == "true") {
								$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
									."FROM public.catalogoespecies, public.ce_atributovalor, public.atributos "
									."WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = 'Imagen'";
								$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')');
							}
						}
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$condition->order = '"pcaatCe".taxonnombre';
							} else if($_GET['order'] == "author") {
								$condition->order = '"pcaatCe".autor';
							} else {
								$condition->order = "fechaelaboracion";
							}
						} else {
							$condition->order = "fechaelaboracion";
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$condition->order = $condition->order." ASC";
							} else {
								$condition->order = $condition->order." DESC";
							}
						} else {
							$condition->order = $condition->order." DESC";
						}
						$condition->limit=20;
						$condition->offset=$offset;
						$models = Catalogoespecies::model()->findAll($condition);
						$condition->limit=null;
						$condition->offset=null;
						$countreg = Catalogoespecies::model()->count($condition);
					}
					//$this->render('index');
					//Yii::app()->end();
				} else {
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Full list is not implemented for model <b>%s</b>', $_GET['model']) );
					Yii::app()->end();
				}
				break;
			case 'fichasresumenhumedal':
				if(isset($_GET['page'])) {
					$offset = ($_GET['page'] - 1) * 20;
					if(isset($_GET['priorityimages'])) {
						$queryWithImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) AND "public".humedales_y_paramos.tipo = \'Humedal\' ';
						$queryWithoutImages='SELECT "t"."catalogoespecies_id", "t"."citacion_id", "t"."contacto_id", "t"."fechaactualizacion", "t"."fechaelaboracion", "t"."titulometadato", "t"."jerarquianombrescomunes" FROM "catalogoespecies" "t" INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id") INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id WHERE "verificacionce".estado_id = 2 AND "t".catalogoespecies_id NOT IN ((SELECT DISTINCT catalogoespecies.catalogoespecies_id FROM public.catalogoespecies, public.ce_atributovalor, public.atributos WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = \'Imagen\')) AND "public".humedales_y_paramos.tipo = \'Humedal\' ';
						if(isset($_GET['scientificname'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['scientificname']).'%\' ';
						}
						if(isset($_GET['taxon'])) {
							$queryWithImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
							$queryWithoutImages .= 'AND LOWER("pcaatCe".taxoncompleto) LIKE \'%'.strtolower($_GET['taxon']).'%\' ';
						}
						if(isset($_GET['id'])) {
							$queryWithImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id = '.$_GET['id'].' ';
						}
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$queryWithImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
							$queryWithoutImages .= 'AND t.catalogoespecies_id IN ('.$sql.') ';
						}
						if(isset($_GET['query'])) {
							$queryWithImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';
							$queryWithoutImages .= 'AND (LOWER("pcaatCe".taxonnombre) LIKE \'%'.strtolower($_GET['query']).'%\' ';							
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['query'])."%'";
							$queryWithImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
							$queryWithoutImages .= 'OR t.catalogoespecies_id IN ('.$sql.') ) ';
						}
						$queryWithImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						$queryWithoutImages .= 'AND "pcaatCe".taxonnombre <> \'\' ';
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$queryWithImages .= 'ORDER BY "pcaatCe".taxonnombre ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".taxonnombre ';
							} else if($_GET['order'] == "author") {
								$queryWithImages .= 'ORDER BY "pcaatCe".autor ';
								$queryWithoutImages .= 'ORDER BY "pcaatCe".autor ';
							} else {
								$queryWithImages .= 'ORDER BY fechaelaboracion ';
								$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
							}
						} else {
							$queryWithImages .= 'ORDER BY fechaelaboracion ';
							$queryWithoutImages .= 'ORDER BY fechaelaboracion ';
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$queryWithImages .= 'ASC';
								$queryWithoutImages .= 'ASC';
							} else {
								$queryWithImages .= 'DESC';
								$queryWithoutImages .= 'DESC';
							}
						} else {
							$queryWithImages .= 'DESC';
							$queryWithoutImages .= 'DESC';
						}
						$unionSQL = '('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.') LIMIT 20 OFFSET '.$offset;
						$unionSQLALL = 'select count(*) FROM (('.$queryWithImages.') UNION ALL ('.$queryWithoutImages.')) AS totalRegs';
						$models = Catalogoespecies::model()->findAllBySql($unionSQL);
						$countreg = Catalogoespecies::model()->countBySql($unionSQLALL);
					} else {
						$condition= new CDbCriteria();
						$condition->join = 'INNER JOIN "pcaat_ce" "pcaatCe" ON ("pcaatCe"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->join .= 'INNER JOIN "verificacionce" "verificacionce" ON ("verificacionce"."catalogoespecies_id"="t"."catalogoespecies_id")';
						$condition->join .= 'INNER JOIN "public".humedales_y_paramos ON "public".humedales_y_paramos.catalogoespecies_id = "t".catalogoespecies_id';
						$condition->addCondition('"public".humedales_y_paramos.tipo = \'Humedal\'');
						$condition->addCondition('"verificacionce".estado_id = 2');
						//$condition->with = array('pcaatCe', 'citacion', 'verificacionce', 'pctesaurosCes', 'pcdepartamentosCes', 'pcregionnaturalCes', 'pccorporacionesCes', 'pcorganizacionesCes', 'ceAtributovalors');
						if(isset($_GET['scientificname'])) {
							$condition->compare('LOWER("pcaatCe".taxonnombre)', strtolower($_GET['scientificname']), true );
						}
						if(isset($_GET['taxon'])) {
							$condition->compare('LOWER("pcaatCe".taxoncompleto)', strtolower($_GET['taxon']), true );
						}
						if(isset($_GET['id'])) {
							$condition->compare('t.catalogoespecies_id',$_GET['id']);
						}
						$sql='';
						if(isset($_GET['commonname'])) {
							$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
								."FROM catalogoespecies "
								."INNER JOIN pctesauros_ce ON catalogoespecies.catalogoespecies_id = pctesauros_ce.catalogoespecies_id "
								."WHERE LOWER(pctesauros_ce.tesauronombre) LIKE '%".strtolower($_GET['commonname'])."%'";
							$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')', 'OR');
						}
						if(isset($_GET['onlyimages'])) {
							if($_GET['onlyimages'] == "true") {
								$sql = "SELECT DISTINCT catalogoespecies.catalogoespecies_id "
									."FROM public.catalogoespecies, public.ce_atributovalor, public.atributos "
									."WHERE catalogoespecies.catalogoespecies_id = ce_atributovalor.catalogoespecies_id AND ce_atributovalor.id_atributo = atributos.id AND atributos.nombre = 'Imagen'";
								$condition->addCondition('t.catalogoespecies_id IN ('.$sql.')');
							}
						}
						if(isset($_GET['order'])) {
							if($_GET['order'] == "scientificname") {
								$condition->order = '"pcaatCe".taxonnombre';
							} else if($_GET['order'] == "author") {
								$condition->order = '"pcaatCe".autor';
							} else {
								$condition->order = "fechaelaboracion";
							}
						} else {
							$condition->order = "fechaelaboracion";
						}
						if(isset($_GET['orderdirection'])) {
							if($_GET['orderdirection'] == "asc") {
								$condition->order = $condition->order." ASC";
							} else {
								$condition->order = $condition->order." DESC";
							}
						} else {
							$condition->order = $condition->order." DESC";
						}
						$condition->limit=20;
						$condition->offset=$offset;
						$models = Catalogoespecies::model()->findAll($condition);
						$condition->limit=null;
						$condition->offset=null;
						$countreg = Catalogoespecies::model()->count($condition);
					}
					//$this->render('index');
					//Yii::app()->end();
				} else {
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Full list is not implemented for model <b>%s</b>', $_GET['model']) );
					Yii::app()->end();
				}
				break;
				
				case 'external_images':
					if (isset($_GET['taxon_nombre']) && $_GET['taxon_nombre'] != '') {
					
						$externalImage = Yii::app()->db->createCommand()
						->select('external_images.*')
						->from('public.external_images')
						->where("external_images.taxonnombre = '".$_GET['taxon_nombre']."'")
						->order('external_images.id')
						->queryAll();
					
						if(is_null($externalImage)){
							$this->_sendResponse(404, 'No Items with image has been found with id '.$_GET['id']);
						}else{
							$this->_sendResponse(200, CJSON::encode($externalImage));
							//print_r($externalImage);
						}
					}
				break;
				
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
				'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
				$_GET['model']) );
				Yii::app()->end();
		}
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200, CJSON::encode('No items where found'));
		} else {
			// Prepare response
			$rows = array();
			if($_GET['model'] == "fichasresumen" || $_GET['model'] == "fichasresumenparamo" || $_GET['model'] == "fichasresumenhumedal") {
				$rows["data"] = [];
				$rows["total_fichas"] = $countreg;
				$counter = 0;
				foreach($models as $model) {
					$rows["data"][$counter]["id"] = $model->catalogoespecies_id;
					$rows["data"][$counter]["taxon_nombre"] = ($model->pcaatCe->taxonnombre != "" ? $model->pcaatCe->taxonnombre : null);
					$rows["data"][$counter]["autor"] = ($model->pcaatCe->autor != "" ? $model->pcaatCe->autor : null);
					if (preg_match('/Reino(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["reino"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Phylum(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["phylum"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Clase(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["clase"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Orden(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["orden"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Familia(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["familia"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Género(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if(preg_match('/Genero(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if (preg_match('/Especie(.*)/is', $model->pcaatCe->taxoncompleto, $matches)) {
						$rows["data"][$counter]["especie"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
					}
					if(isset($model->pctesaurosCes)) {
						$nombresComunes=$model->pctesaurosCes;
						$rows["data"][$counter]["nombres_comunes"] = [];
						$counterNombreComun = 0;
						foreach($nombresComunes as $nombreComun) {
							$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["nombre"] = ($nombreComun->tesauronombre != "" ? $nombreComun->tesauronombre : null);
							$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["idioma"] = ($nombreComun->idioma != "" ? $nombreComun->idioma : null);
							$rows["data"][$counter]["nombres_comunes"][$counterNombreComun]["region_geografica"] = ($nombreComun->regionesgeograficas != "" ? $nombreComun->regionesgeograficas : null);
							$counterNombreComun++;
						}
					}
					if(isset($model->pcdepartamentosCes)) {
						$departamentos=$model->pcdepartamentosCes;
						$rows["data"][$counter]["departamentos"] = [];
						$counterDepartamento = 0;
						foreach($departamentos as $departamento) {
							$rows["data"][$counter]["departamentos"][$counterDepartamento]["departamento"] = ($departamento->departamento->departamento != "" ? $departamento->departamento->departamento : null);
							$counterDepartamento++;
						}
					}
					if(isset($model->ceAtributovalors)) {
						$atributos=$model->ceAtributovalors;
						$rows["data"][$counter]["imagenes"]["imagen"] = [];
						$counterImagen = 0;
						foreach($atributos as $atributo) {
							if(isset($atributo->atributo)) {
								if($atributo->atributo->nombre == "Imagen") {
									$rows["data"][$counter]["imagenes"]["imagen"][$counterImagen] = $atributo->valor0->valor;
									$counterImagen++;
								}
							}
						}
					}
					if(isset($model->ceAtributovalors)) {
						$atributos=$model->ceAtributovalors;
						foreach($atributos as $atributo) {
							if(isset($atributo->atributo)) {
								if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
									$rows["data"][$counter]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
								}
							}
						}
					}
					if(isset($rows["data"][$counter]["imagenes"]["imagen"])) {
						$counterArray=0;
						foreach ($rows["data"][$counter]["imagenes"]["imagen"] as $imagen) {
							$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
							$extension = end(explode('.', $imagen));
							$filename = current(explode('.', $imagen));
							if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
								mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
							}
							if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
								$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension, 140, 140, 1);
							}
							if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
								$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension, 270, 270, 1);
							}
							//$rows["data"][$counter]["imagenes"]["imagenThumb140"][$counterArray] = 'http://'.$_SERVER['HTTP_HOST'].'/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode($filename).'_140x140.'.$extension;
							//$rows["data"][$counter]["imagenes"]["imagenThumb270"][$counterArray] = 'http://'.$_SERVER['HTTP_HOST'].'/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode($filename).'_270x270.'.$extension;
							//$rows["data"][$counter]["imagenes"]["imagen"][$counterArray] = 'http://'.$_SERVER['HTTP_HOST'].'/imagen/'.rawurlencode($imagen);
							if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
								$rows["data"][$counter]["imagenes"]["imagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_140x140.'.$extension;
							}
							if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
								$rows["data"][$counter]["imagenes"]["imagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_270x270.'.$extension;
							}
							$rows["data"][$counter]["imagenes"]["imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
							$counterArray++;
						}
					}
					$counter++;
				}
			} else if($_GET['model'] == "fichas") {
				foreach($models as $model) {
					$rows[$model->catalogoespecies_id] = $model->attributes;
					if(isset($model->pcaatCe)) {
						$rows[$model->catalogoespecies_id]["info_taxonomica"] = $model->pcaatCe->attributes;
					}
					if(isset($model->contacto)) {
						$rows[$model->catalogoespecies_id]["contacto"] = $model->contacto->attributes;
						if(isset($model->contacto->idReferenteGeografico)) {
							$rows[$model->catalogoespecies_id]["contacto"]["pais"] = $model->contacto->idReferenteGeografico->idPais->paisAbreviatura->paisAbreviatura->pais_nombre;
							$rows[$model->catalogoespecies_id]["contacto"]["departamento_estado_provincia"] = $model->contacto->idReferenteGeografico->idSub->subAbreviatura->sub_nombre;
							$rows[$model->catalogoespecies_id]["contacto"]["municipio"] = $model->contacto->idReferenteGeografico->idCm->ciudad_municipio_nombre;
						}
					}
					if(isset($model->pctesaurosCes)) {
						$nombresComunes=$model->pctesaurosCes;
						foreach($nombresComunes as $nombreComun) {
							$rows[$model->catalogoespecies_id]["nombres_comunes"][]=$nombreComun->attributes;
						}
					}
					if(isset($model->pcdepartamentosCes)) {
						$departamentos=$model->pcdepartamentosCes;
						foreach($departamentos as $departamento) {
							$rows[$model->catalogoespecies_id]["distribucion_geografica"]["departamentos"][]=$departamento->departamento->departamento;
						}
					}
					if(isset($model->pcorganizacionesCes)) {
						$organizaciones=$model->pcorganizacionesCes;
						foreach($organizaciones as $organizacio) {
							$rows[$model->catalogoespecies_id]["distribucion_geografica"]["organizaciones"][]=$organizacio->organizacion->organizacion;
						}
					}
					if(isset($model->ceAtributovalors)) {
						$atributos=$model->ceAtributovalors;
						foreach($atributos as $atributo) {
							if(isset($atributo->atributo)) {
								if($atributo->atributo->nombre == "Imagen") {
									$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
								}
							}
						}
					}
					if(isset($rows[$model->catalogoespecies_id]["atributos"]["Imagen"])) {
						$counterArray=0;
						foreach ($rows[$model->catalogoespecies_id]["atributos"]["Imagen"] as $imagen) {
							$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
							$extension = end(explode('.', $imagen));
							$filename = current(explode('.', $imagen));
							if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
								mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
							}
							if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_140x140.'.$extension)) {
								$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_140x140.'.$extension, 140, 140, 1);
							}
							if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_270x270.'.$extension)) {
								$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.$filename.'_270x270.'.$extension, 270, 270, 1);
							}
							$rows[$model->catalogoespecies_id]["atributos"]["ImagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode($filename).'_140x140.'.$extension;
							$rows[$model->catalogoespecies_id]["atributos"]["ImagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode($filename).'_270x270.'.$extension;
							$rows[$model->catalogoespecies_id]["atributos"]["Imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
							$counterArray++;
						}
					}
				}
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionListFull()
	{
		// Get the respective model instance
		switch($_GET['model'])
		{
			case 'fichas':
				if(isset($_GET['page'])) {
					$offset = ($_GET['page'] - 1) * 20;
					$condition= new CDbCriteria();
					//$condition->with = array('pcaatCe', 'citacion', 'verificacionce', 'pctesaurosCes', 'pcdepartamentosCes', 'pcregionnaturalCes', 'pccorporacionesCes', 'pcorganizacionesCes', 'ceAtributovalors');
					$condition->order = "fechaelaboracion DESC, catalogoespecies_id DESC";
					$condition->limit=20;
					$condition->offset=$offset;
					$models = Catalogoespecies::model()->findAll($condition);
				} else {
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Full list is not implemented for model <b>%s</b>', $_GET['model']) );
					Yii::app()->end();
				}
				break;
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
				'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
				$_GET['model']) );
				Yii::app()->end();
		}
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200,
					sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
		} else {
			// Prepare response
			$rows = array();
			foreach($models as $model) {
				$rows[$model->catalogoespecies_id] = $model->attributes;
				if(isset($model->pcaatCe)) {
					$rows[$model->catalogoespecies_id]["info_taxonomica"] = $model->pcaatCe->attributes;
				}
				if(isset($model->contacto)) {
					$rows[$model->catalogoespecies_id]["contacto"] = $model->contacto->attributes;
					if(isset($model->contacto->idReferenteGeografico)) {
						$rows[$model->catalogoespecies_id]["contacto"]["pais"] = $model->contacto->idReferenteGeografico->idPais->paisAbreviatura->paisAbreviatura->pais_nombre;
						$rows[$model->catalogoespecies_id]["contacto"]["departamento_estado_provincia"] = $model->contacto->idReferenteGeografico->idSub->subAbreviatura->sub_nombre;
						$rows[$model->catalogoespecies_id]["contacto"]["municipio"] = $model->contacto->idReferenteGeografico->idCm->ciudad_municipio_nombre;
					}
				}
				if(isset($model->citacion)) {
					$rows[$model->catalogoespecies_id]["citacion"] = $model->citacion->attributes;
					if(isset($model->citacion->citaciontipo)) {
						$rows[$model->catalogoespecies_id]["citacion"]["citacion_tipo_nombre"] = $model->citacion->citaciontipo->citaciontipo_nombre;
					}
					if(isset($model->citacion->repositorioCitacion)) {
						$rows[$model->catalogoespecies_id]["citacion"]["persona_repositorio_citacion"] = $model->citacion->repositorioCitacion->persona;
						$rows[$model->catalogoespecies_id]["citacion"]["organizacion_repositorio_citacion"] = $model->citacion->repositorioCitacion->organizacion;
					}
					if(isset($model->citacion->citacionSuperior)) {
						$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"] = $model->citacion->citacionSuperior->attributes;
						if(isset($model->citacion->citacionSuperior->citaciontipo)) {
							$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["citacion_tipo_nombre"] = $model->citacion->citacionSuperior->citaciontipo->citaciontipo_nombre;
						}
						if(isset($model->citacion->citacionSuperior->repositorioCitacion)) {
							$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["persona_repositorio_citacion"] = $model->citacion->repositorioCitacion->persona;
							$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["organizacion_repositorio_citacion"] = $model->citacion->citacionSuperior->repositorioCitacion->organizacion;
						}
					}
				}
				if(isset($model->verificacionce)) {
					$rows[$model->catalogoespecies_id]["verificacion"]["estado_de_verificacion"] = $model->verificacionce->estado->nombre;
					$rows[$model->catalogoespecies_id]["verificacion"]["fecha_de_ultima_verificacion"] = $model->verificacionce->fecha;
					$rows[$model->catalogoespecies_id]["verificacion"]["comentarios"] = $model->verificacionce->comentarios;
				}
				if(isset($model->pctesaurosCes)) {
					$nombresComunes=$model->pctesaurosCes;
					foreach($nombresComunes as $nombreComun) {
						$rows[$model->catalogoespecies_id]["nombres_comunes"][]=$nombreComun->attributes;
					}
				}
				if(isset($model->pcdepartamentosCes)) {
					$departamentos=$model->pcdepartamentosCes;
					foreach($departamentos as $departamento) {
						$rows[$model->catalogoespecies_id]["distribucion_geografica"]["departamentos"][]=$departamento->departamento->departamento;
					}
				}
				if(isset($model->pcregionnaturalCes)) {
					$regionesNaturales=$model->pcregionnaturalCes;
					foreach($regionesNaturales as $regionNatural) {
						$rows[$model->catalogoespecies_id]["distribucion_geografica"]["regiones_naturales"][]=$regionNatural->region_natural->region_natural;
					}
				}
				if(isset($model->pccorporacionesCes)) {
					$corporacionesAutonomasRegionales=$model->pccorporacionesCes;
					foreach($corporacionesAutonomasRegionales as $corporacionAutonomaRegional) {
						$rows[$model->catalogoespecies_id]["distribucion_geografica"]["corporaciones_autonomas_regionales"][]=$corporacionAutonomaRegional->corporacion->corporacion;
					}
				}
				if(isset($model->pcorganizacionesCes)) {
					$organizaciones=$model->pcorganizacionesCes;
					foreach($organizaciones as $organizacio) {
						$rows[$model->catalogoespecies_id]["distribucion_geografica"]["organizaciones"][]=$organizacio->organizacion->organizacion;
					}
				}
				if(isset($model->ceAtributovalors)) {
					$atributos=$model->ceAtributovalors;
					foreach($atributos as $atributo) {
						if(isset($atributo->atributo)) {
							if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
								$rows[$model->catalogoespecies_id]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
							} else if($atributo->atributo->nombre == "Referencias bibliográficas") {
								$citacion=Citacion::model()->findByPk($atributo->valor0->valor);
								$arreglo=$citacion->attributes;
								if(isset($citacion->citaciontipo)) {
									$arreglo["citacion_tipo_nombre"]=$citacion->citaciontipo->citaciontipo_nombre;
								}
								if(isset($citacion->repositorioCitacion)) {
									$arreglo["persona_repositorio_citacion"]=$citacion->repositorioCitacion->persona;
									$arreglo["organizacion_repositorio_citacion"]=$citacion->repositorioCitacion->organizacion;
								}
								$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$arreglo;
							} else if($atributo->atributo->nombre == "Autor(es)" || $atributo->atributo->nombre == "Editor(es)" || $atributo->atributo->nombre == "Revisor(es)" || $atributo->atributo->nombre == "Colaborador(es)") {
								$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
								$arreglo2=$contacto->attributes;
								if(isset($contacto->idReferenteGeografico)) {
									$arreglo2["pais"] = $contacto->idReferenteGeografico->idPais->paisAbreviatura->paisAbreviatura->pais_nombre;
									$arreglo2["departamento_estado_provincia"] = $contacto->idReferenteGeografico->idSub->subAbreviatura->sub_nombre;
									$arreglo2["municipio"] = $contacto->idReferenteGeografico->idCm->ciudad_municipio_nombre;
								}
								$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$arreglo2;
							} else if($atributo->etiqueta != 2) {
								$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
							}
						}
					}
				}
			}
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}
	
	public function actionView()
	{
		// Check if id was submitted via GET
		if(!isset($_GET['id']))
			$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
		
		switch($_GET['model'])
		{
			// Find respective model
			case 'ficha':
				$model = Catalogoespecies::model()->findByPk($_GET['id']);
				break;
			default:
				$this->_sendResponse(501, sprintf(
				'Mode <b>view</b> is not implemented for model <b>%s</b>',
				$_GET['model']) );
				Yii::app()->end();
		}
		// Did we find the requested model? If not, raise an error
		if(is_null($model)) {
			$this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
		} else {
			// Prepare response
			$rows = array();
			$rows[$model->catalogoespecies_id] = $model->attributes;
			if(isset($model->pcaatCe)) {
				$rows[$model->catalogoespecies_id]["info_taxonomica"] = $model->pcaatCe->attributes;
				if (preg_match('/Reino(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["reino"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Phylum(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["phylum"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Clase(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["clase"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Orden(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["orden"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Familia(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["familia"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Género(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if(preg_match('/Genero(.*?)>>/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["genero"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
				if (preg_match('/Especie(.*)/is', $model->pcaatCe->taxoncompleto, $matches)) {
					$rows[$model->catalogoespecies_id]["especie"] = (trim($matches[1]) != "" ? trim($matches[1]) : null);
				}
			}
			if(isset($model->contacto)) {
				$rows[$model->catalogoespecies_id]["contacto"] = $model->contacto->attributes;
				if(isset($model->contacto->idReferenteGeografico)) {
					$rows[$model->catalogoespecies_id]["contacto"]["pais"] = $model->contacto->idReferenteGeografico->idPais->paisAbreviatura->paisAbreviatura->pais_nombre;
					$rows[$model->catalogoespecies_id]["contacto"]["departamento_estado_provincia"] = $model->contacto->idReferenteGeografico->idSub->subAbreviatura->sub_nombre;
					$rows[$model->catalogoespecies_id]["contacto"]["municipio"] = $model->contacto->idReferenteGeografico->idCm->ciudad_municipio_nombre;
				}
			}
			if(isset($model->citacion)) {
				$rows[$model->catalogoespecies_id]["citacion"] = $model->citacion->attributes;
				if(isset($model->citacion->citaciontipo)) {
					$rows[$model->catalogoespecies_id]["citacion"]["citacion_tipo_nombre"] = $model->citacion->citaciontipo->citaciontipo_nombre;
				}
				if(isset($model->citacion->repositorioCitacion)) {
					$rows[$model->catalogoespecies_id]["citacion"]["persona_repositorio_citacion"] = $model->citacion->repositorioCitacion->persona;
					$rows[$model->catalogoespecies_id]["citacion"]["organizacion_repositorio_citacion"] = $model->citacion->repositorioCitacion->organizacion;
				}
				if(isset($model->citacion->citacionSuperior)) {
					$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"] = $model->citacion->citacionSuperior->attributes;
					if(isset($model->citacion->citacionSuperior->citaciontipo)) {
						$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["citacion_tipo_nombre"] = $model->citacion->citacionSuperior->citaciontipo->citaciontipo_nombre;
					}
					if(isset($model->citacion->citacionSuperior->repositorioCitacion)) {
						$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["persona_repositorio_citacion"] = $model->citacion->repositorioCitacion->persona;
						$rows[$model->catalogoespecies_id]["citacion"]["citacion_superior"]["organizacion_repositorio_citacion"] = $model->citacion->citacionSuperior->repositorioCitacion->organizacion;
					}
				}
			}
			if(isset($model->verificacionce)) {
				$rows[$model->catalogoespecies_id]["verificacion"]["estado_de_verificacion"] = $model->verificacionce->estado->nombre;
				$rows[$model->catalogoespecies_id]["verificacion"]["fecha_de_ultima_verificacion"] = $model->verificacionce->fecha;
				$rows[$model->catalogoespecies_id]["verificacion"]["comentarios"] = $model->verificacionce->comentarios;
			}
			if(isset($model->pctesaurosCes)) {
				$nombresComunes=$model->pctesaurosCes;
				foreach($nombresComunes as $nombreComun) {
					$rows[$model->catalogoespecies_id]["nombres_comunes"][]=$nombreComun->attributes;
				}
			}
			if(isset($model->pcdepartamentosCes)) {
				$departamentos=$model->pcdepartamentosCes;
				foreach($departamentos as $departamento) {
					$rows[$model->catalogoespecies_id]["distribucion_geografica"]["departamentos"][]=$departamento->departamento->departamento;
				}
			}
			if(isset($model->pcregionnaturalCes)) {
				$regionesNaturales=$model->pcregionnaturalCes;
				foreach($regionesNaturales as $regionNatural) {
					$rows[$model->catalogoespecies_id]["distribucion_geografica"]["regiones_naturales"][]=$regionNatural->region_natural->region_natural;
				}
			}
			if(isset($model->pccorporacionesCes)) {
				$corporacionesAutonomasRegionales=$model->pccorporacionesCes;
				foreach($corporacionesAutonomasRegionales as $corporacionAutonomaRegional) {
					$rows[$model->catalogoespecies_id]["distribucion_geografica"]["corporaciones_autonomas_regionales"][]=$corporacionAutonomaRegional->corporacion->corporacion;
				}
			}
			if(isset($model->pcorganizacionesCes)) {
				$organizaciones=$model->pcorganizacionesCes;
				foreach($organizaciones as $organizacio) {
					$rows[$model->catalogoespecies_id]["distribucion_geografica"]["organizaciones"][]=$organizacio->organizacion->organizacion;
				}
			}
			if(isset($model->ceAtributovalors)) {
				$atributos=$model->ceAtributovalors;
				foreach($atributos as $atributo) {
					if(isset($atributo->atributo)) {
						if($atributo->etiqueta == 3 || $atributo->etiqueta == 4) {
							$rows[$model->catalogoespecies_id]["atributos"]["Estado de amenaza según categorías UICN"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
						} else if($atributo->atributo->nombre == "Referencias bibliográficas") {
							$citacion=Citacion::model()->findByPk($atributo->valor0->valor);
							$arreglo=$citacion->attributes;
							if(isset($citacion->citaciontipo)) {
								$arreglo["citacion_tipo_nombre"]=$citacion->citaciontipo->citaciontipo_nombre;
							}
							if(isset($citacion->repositorioCitacion)) {
								$arreglo["persona_repositorio_citacion"]=$citacion->repositorioCitacion->persona;
								$arreglo["organizacion_repositorio_citacion"]=$citacion->repositorioCitacion->organizacion;
							}
							$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$arreglo;
						} else if($atributo->atributo->nombre == "Autor(es)" || $atributo->atributo->nombre == "Editor(es)" || $atributo->atributo->nombre == "Revisor(es)" || $atributo->atributo->nombre == "Colaborador(es)") {
							$contacto=Contactos::model()->findByPk($atributo->valor0->valor);
							$arreglo2=$contacto->attributes;
							if(isset($contacto->idReferenteGeografico)) {
								$arreglo2["pais"] = $contacto->idReferenteGeografico->idPais->paisAbreviatura->paisAbreviatura->pais_nombre;
								$arreglo2["departamento_estado_provincia"] = $contacto->idReferenteGeografico->idSub->subAbreviatura->sub_nombre;
								$arreglo2["municipio"] = $contacto->idReferenteGeografico->idCm->ciudad_municipio_nombre;
							}
							$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$arreglo2;
						} else if($atributo->etiqueta != 2) {
							$rows[$model->catalogoespecies_id]["atributos"][$atributo->atributo->nombre][]=$atributo->valor0->valor;
						}
					}
				}
			}

			// Send the response
			//$this->_sendResponse(200, CJSON::encode($rows));
			if(isset($rows[$model->catalogoespecies_id]["atributos"]["Imagen"])) {
				$counterArray=0;
				foreach ($rows[$model->catalogoespecies_id]["atributos"]["Imagen"] as $imagen) {
					$images_path = $_SERVER['DOCUMENT_ROOT'].'/imagen';
					$extension = end(explode('.', $imagen));
					$filename = current(explode('.', $imagen));
					if (!is_dir($images_path.'/resampled/'.$model->catalogoespecies_id)) {
						mkdir($images_path.'/resampled/'.$model->catalogoespecies_id, 0755, true);
					}
					if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
						$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension, 140, 140, 1);
					}
					if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
						$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension, 270, 270, 1);
					}
					if(!file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_370x370.'.$extension)) {
						$this->image_resize($images_path.'/'.$imagen, $images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_370x370.'.$extension, 370, 370, 1);
					}
					if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_140x140.'.$extension)) {
						$rows[$model->catalogoespecies_id]["atributos"]["imagenThumb140"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_140x140.'.$extension;
					}
					if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_270x270.'.$extension)) {
						$rows[$model->catalogoespecies_id]["atributos"]["imagenThumb270"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_270x270.'.$extension;
					}
					if(file_exists($images_path.'/resampled/'.$model->catalogoespecies_id.'/'.str_replace(' ', '_', $filename).'_370x370.'.$extension)) {
						$rows[$model->catalogoespecies_id]["atributos"]["imagenThumb370"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/resampled/'.$model->catalogoespecies_id.'/'.rawurlencode(str_replace(' ', '_', $filename)).'_370x370.'.$extension;
					}
					$rows[$model->catalogoespecies_id]["atributos"]["Imagen"][$counterArray] = 'http://www.biodiversidad.co:3000/imagen/'.rawurlencode($imagen);
					$counterArray++;
				}
			}
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'application/json')
	{
		// set the status
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		// and the content type
		header('Content-type: ' . $content_type);
		header('Access-control-allow-origin: *');
		header('Access-Control-Allow-Methods: GET');

		if(isset($_GET['callback'])) {
			if($this->_is_valid_callback($_GET['callback'])) {
				$body = $_GET['callback'].'('.$body.')';
			} else {
				$body = $_GET['callback'].'('.$body.')';
			}
		}

		if(isset($_GET['jsonp'])) {
			if($this->_is_valid_callback($_GET['jsonp'])) {
				$body = $_GET['jsonp'].'('.$body.')';
			} else {
				$body = $_GET['jsonp'].'('.$body.')';
			}
		}
	
		// pages with body are easy
		if($body != '')
		{
			// send the body
			echo $body;
		}
		// we need to create the body if none is passed
		else
		{
			// create some body messages
			$message = '';
	
			// this is purely optional, but makes the pages a little nicer to read
			// for your users.  Since you won't likely send a lot of difaferent status codes,
			// this also shouldn't be too ponderous to maintain
			switch($status)
			{
				case 401:
					$message = 'You must be authorized to view this page.';
					break;
				case 404:
					$message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
					break;
				case 500:
					$message = 'The server encountered an error processing your request.';
					break;
				case 501:
					$message = 'The requested method is not implemented.';
					break;
			}
	
			// servers don't always have a signature turned on
			// (this is an apache directive "ServerSignature On")
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
	
			// this should be templated in a real-world solution
			$body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
	
			echo $body;
		}
		Yii::app()->end();
	}
	
	public function actionPdfCompiler(){
		if ($_GET['id'] != 0) {
			$modelContacto = Contactos::model()->findByPk($_GET['id']);
			$emailContacto = $modelContacto->correo_electronico;
			$modelVer 	= Catalogoespecies::model()->with('verificacionce')->findAll('"verificacionce"."contacto_id"=:contactoId', array(':contactoId' => $emailContacto));
	
			if (count($modelVer) > 0) {
				for ($i = 0; $i < count($modelVer); $i++) {
					//$ids[] = $modelVer[$i]->catalogoespecies_id;
					exec("phantomjs rasterize.js http://localhost:4000/fichas/".$modelVer[$i]->catalogoespecies_id." Ficha_".$modelVer[$i]->catalogoespecies_id.".pdf Letter");
					//exec("phantomjs rasterize.js http://http://192.168.206.34:4000/fichas/".$modelVer[$i]->catalogoespecies_id." Ficha_".$modelVer[$i]->catalogoespecies_id.".png ");
				}
			}
	
		}
	}
	
	private function _getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
				200 => 'OK',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}

	private function _is_valid_callback($subject) {
		$identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

		$reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case',
			'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue', 
			'for', 'switch', 'while', 'debugger', 'function', 'this', 'with', 
			'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum', 
			'extends', 'super', 'const', 'export', 'import', 'implements', 'let', 
			'private', 'public', 'yield', 'interface', 'package', 'protected', 
			'static', 'null', 'true', 'false');

		return preg_match($identifier_syntax, $subject) && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
	}
	
	protected function beforeAction($action)
	{
		foreach (Yii::app()->log->routes as $route)
		{
			if ($route instanceof CWebLogRoute)
			{
				$route->enabled = false;
			}
		}
		return true;
	}

	private function image_resize($src, $dst, $width, $height, $crop=0) {
		if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

		$type = strtolower(substr(strrchr($src,"."),1));
		if($type == 'jpeg') $type = 'jpg';
		switch($type) {
			//case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		// resize
		if($crop) {
			if($w < $width or $h < $height) return "Picture is too small!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		} else {
			if($w < $width and $h < $height) 
				return "Picture is too small!";$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		}

		$new = imagecreatetruecolor($width, $height);

		// preserve transparency
		if($type == "gif" or $type == "png") {
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}

		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		switch($type) {
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst); break;
			case 'png': imagepng($new, $dst); break;
		}
		return true;
	}

	private function CroppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { 
		//$imgSrc is a FILE - Returns an image resource.
		//getting the image dimensions  
		list($width_orig, $height_orig) = getimagesize($imgSrc);   
		$myImage = imagecreatefromjpeg($imgSrc);
		$ratio_orig = $width_orig/$height_orig;

		if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
			$new_height = $thumbnail_width/$ratio_orig;
			$new_width = $thumbnail_width;
		} else {
			$new_width = $thumbnail_height*$ratio_orig;
			$new_height = $thumbnail_height;
		}

		$x_mid = $new_width/2;  //horizontal middle
		$y_mid = $new_height/2; //vertical middle

		$process = imagecreatetruecolor(round($new_width), round($new_height));
		imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
		$thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
		imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

		imagedestroy($process);
		imagedestroy($myImage);
		return $thumb;
	}
}
?>
