<?php

/**
 * This is the model class for table "pliniancore".
 *
 * The followings are the available columns in table 'pliniancore':
 * @property string $scientificname
 * @property string $institutioncode
 * @property string $taxonrecordid
 * @property string $language
 * @property string $creators
 * @property string $distribution
 * @property string $abstract
 * @property string $kingdom
 * @property string $phylum
 * @property string $class
 * @property string $order1
 * @property string $family
 * @property string $genus
 * @property string $synonyms
 * @property string $authoryearofscientificname
 * @property string $speciespublicationreference
 * @property string $commonnames
 * @property string $typification
 * @property string $globaluniqueidentifier
 * @property string $contributors
 * @property string $habit
 * @property string $lifecycle
 * @property string $reproduction
 * @property string $annualcycle
 * @property string $scientificdescription
 * @property string $briefdescription
 * @property string $feeding
 * @property string $behavior
 * @property string $interactions
 * @property string $chromosomicnumbern
 * @property string $moleculardata
 * @property string $populationbiology
 * @property string $threatstatus
 * @property string $legislation
 * @property string $habitat
 * @property string $territory
 * @property string $endemicity
 * @property string $uses
 * @property string $management
 * @property string $folklore
 * @property string $references1
 * @property string $unstructureddocumentation
 * @property string $otherinformationsources
 * @property string $papers
 * @property string $identificationkeys
 * @property string $migratorydata
 * @property string $ecologicalsignificance
 * @property string $unstructurednaturalhistory
 * @property string $invasivenessdata
 * @property string $targetaudiences
 * @property string $version
 * @property string $urlimage1
 * @property string $captionimage1
 * @property string $urlimage2
 * @property string $urlimage3
 * @property string $captionimage2
 * @property string $captionimage3
 * @property string $datelastmodified
 * @property string $datecreated
 * @property string $estado
 */
class Pliniancore extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pliniancore the static model class
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
		return 'pliniancore';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('scientificname, taxonrecordid, language', 'required'),
			array('scientificname, kingdom, phylum, class, order1, family, genus, speciespublicationreference, threatstatus, urlimage1, urlimage2, urlimage3', 'length', 'max'=>255),
			array('institutioncode, captionimage1, captionimage2, captionimage3', 'length', 'max'=>100),
			array('taxonrecordid, targetaudiences, version, estado', 'length', 'max'=>50),
			array('language', 'length', 'max'=>2),
			array('creators', 'length', 'max'=>1000),
			array('authoryearofscientificname, globaluniqueidentifier', 'length', 'max'=>150),
			array('distribution, abstract, synonyms, commonnames, typification, contributors, habit, lifecycle, reproduction, annualcycle, scientificdescription, briefdescription, feeding, behavior, interactions, chromosomicnumbern, moleculardata, populationbiology, legislation, habitat, territory, endemicity, uses, management, folklore, references1, unstructureddocumentation, otherinformationsources, papers, identificationkeys, migratorydata, ecologicalsignificance, unstructurednaturalhistory, invasivenessdata, datelastmodified, datecreated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('scientificname, institutioncode, taxonrecordid, language, creators, distribution, abstract, kingdom, phylum, class, order1, family, genus, synonyms, authoryearofscientificname, speciespublicationreference, commonnames, typification, globaluniqueidentifier, contributors, habit, lifecycle, reproduction, annualcycle, scientificdescription, briefdescription, feeding, behavior, interactions, chromosomicnumbern, moleculardata, populationbiology, threatstatus, legislation, habitat, territory, endemicity, uses, management, folklore, references1, unstructureddocumentation, otherinformationsources, papers, identificationkeys, migratorydata, ecologicalsignificance, unstructurednaturalhistory, invasivenessdata, targetaudiences, version, urlimage1, captionimage1, urlimage2, urlimage3, captionimage2, captionimage3, datelastmodified, datecreated, estado', 'safe', 'on'=>'search'),
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
			'scientificname' => 'Scientificname',
			'institutioncode' => 'Institutioncode',
			'taxonrecordid' => 'Taxonrecordid',
			'language' => 'Language',
			'creators' => 'Creators',
			'distribution' => 'Distribution',
			'abstract' => 'Abstract',
			'kingdom' => 'Kingdom',
			'phylum' => 'Phylum',
			'class' => 'Class',
			'order1' => 'Order1',
			'family' => 'Family',
			'genus' => 'Genus',
			'synonyms' => 'Synonyms',
			'authoryearofscientificname' => 'Authoryearofscientificname',
			'speciespublicationreference' => 'Speciespublicationreference',
			'commonnames' => 'Commonnames',
			'typification' => 'Typification',
			'globaluniqueidentifier' => 'Globaluniqueidentifier',
			'contributors' => 'Contributors',
			'habit' => 'Habit',
			'lifecycle' => 'Lifecycle',
			'reproduction' => 'Reproduction',
			'annualcycle' => 'Annualcycle',
			'scientificdescription' => 'Scientificdescription',
			'briefdescription' => 'Briefdescription',
			'feeding' => 'Feeding',
			'behavior' => 'Behavior',
			'interactions' => 'Interactions',
			'chromosomicnumbern' => 'Chromosomicnumbern',
			'moleculardata' => 'Moleculardata',
			'populationbiology' => 'Populationbiology',
			'threatstatus' => 'Threatstatus',
			'legislation' => 'Legislation',
			'habitat' => 'Habitat',
			'territory' => 'Territory',
			'endemicity' => 'Endemicity',
			'uses' => 'Uses',
			'management' => 'Management',
			'folklore' => 'Folklore',
			'references1' => 'References1',
			'unstructureddocumentation' => 'Unstructureddocumentation',
			'otherinformationsources' => 'Otherinformationsources',
			'papers' => 'Papers',
			'identificationkeys' => 'Identificationkeys',
			'migratorydata' => 'Migratorydata',
			'ecologicalsignificance' => 'Ecologicalsignificance',
			'unstructurednaturalhistory' => 'Unstructurednaturalhistory',
			'invasivenessdata' => 'Invasivenessdata',
			'targetaudiences' => 'Targetaudiences',
			'version' => 'Version',
			'urlimage1' => 'Urlimage1',
			'captionimage1' => 'Captionimage1',
			'urlimage2' => 'Urlimage2',
			'urlimage3' => 'Urlimage3',
			'captionimage2' => 'Captionimage2',
			'captionimage3' => 'Captionimage3',
			'datelastmodified' => 'Datelastmodified',
			'datecreated' => 'Datecreated',
			'estado' => 'Estado',
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

		$criteria->compare('scientificname',$this->scientificname,true);
		$criteria->compare('institutioncode',$this->institutioncode,true);
		$criteria->compare('taxonrecordid',$this->taxonrecordid,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('creators',$this->creators,true);
		$criteria->compare('distribution',$this->distribution,true);
		$criteria->compare('abstract',$this->abstract,true);
		$criteria->compare('kingdom',$this->kingdom,true);
		$criteria->compare('phylum',$this->phylum,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('order1',$this->order1,true);
		$criteria->compare('family',$this->family,true);
		$criteria->compare('genus',$this->genus,true);
		$criteria->compare('synonyms',$this->synonyms,true);
		$criteria->compare('authoryearofscientificname',$this->authoryearofscientificname,true);
		$criteria->compare('speciespublicationreference',$this->speciespublicationreference,true);
		$criteria->compare('commonnames',$this->commonnames,true);
		$criteria->compare('typification',$this->typification,true);
		$criteria->compare('globaluniqueidentifier',$this->globaluniqueidentifier,true);
		$criteria->compare('contributors',$this->contributors,true);
		$criteria->compare('habit',$this->habit,true);
		$criteria->compare('lifecycle',$this->lifecycle,true);
		$criteria->compare('reproduction',$this->reproduction,true);
		$criteria->compare('annualcycle',$this->annualcycle,true);
		$criteria->compare('scientificdescription',$this->scientificdescription,true);
		$criteria->compare('briefdescription',$this->briefdescription,true);
		$criteria->compare('feeding',$this->feeding,true);
		$criteria->compare('behavior',$this->behavior,true);
		$criteria->compare('interactions',$this->interactions,true);
		$criteria->compare('chromosomicnumbern',$this->chromosomicnumbern,true);
		$criteria->compare('moleculardata',$this->moleculardata,true);
		$criteria->compare('populationbiology',$this->populationbiology,true);
		$criteria->compare('threatstatus',$this->threatstatus,true);
		$criteria->compare('legislation',$this->legislation,true);
		$criteria->compare('habitat',$this->habitat,true);
		$criteria->compare('territory',$this->territory,true);
		$criteria->compare('endemicity',$this->endemicity,true);
		$criteria->compare('uses',$this->uses,true);
		$criteria->compare('management',$this->management,true);
		$criteria->compare('folklore',$this->folklore,true);
		$criteria->compare('references1',$this->references1,true);
		$criteria->compare('unstructureddocumentation',$this->unstructureddocumentation,true);
		$criteria->compare('otherinformationsources',$this->otherinformationsources,true);
		$criteria->compare('papers',$this->papers,true);
		$criteria->compare('identificationkeys',$this->identificationkeys,true);
		$criteria->compare('migratorydata',$this->migratorydata,true);
		$criteria->compare('ecologicalsignificance',$this->ecologicalsignificance,true);
		$criteria->compare('unstructurednaturalhistory',$this->unstructurednaturalhistory,true);
		$criteria->compare('invasivenessdata',$this->invasivenessdata,true);
		$criteria->compare('targetaudiences',$this->targetaudiences,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('urlimage1',$this->urlimage1,true);
		$criteria->compare('captionimage1',$this->captionimage1,true);
		$criteria->compare('urlimage2',$this->urlimage2,true);
		$criteria->compare('urlimage3',$this->urlimage3,true);
		$criteria->compare('captionimage2',$this->captionimage2,true);
		$criteria->compare('captionimage3',$this->captionimage3,true);
		$criteria->compare('datelastmodified',$this->datelastmodified,true);
		$criteria->compare('datecreated',$this->datecreated,true);
		$criteria->compare('estado',$this->estado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}