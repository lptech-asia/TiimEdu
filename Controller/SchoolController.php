<?php
/**
 * Class VSTiimeduPublicController
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class SchoolController extends VSControllerPublic
{
    
    private static $__instance = null;
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }
    public function __construct()
    {
        parent::__construct();
        $this->model = VSModel::getInstance()->load('Tiimedu');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
        $this->modelProgram = VSModel::getInstance()->load($this->modelSchool, 'SchoolPrograms');
        $this->modelLiving = VSModel::getInstance()->load($this->modelSchool, 'SchoolLiving');
        $this->modelScholarships = VSModel::getInstance()->load($this->modelSchool, 'SchoolScholarships');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries')->addModel('modelSchool', $this->modelSchool);
        $this->modelSchool->addModel('modelProgram', $this->modelProgram);
        $this->currentUser = $this->modelSchool->getLoggined();
        $this->school = $this->modelSchool->where('user_id', $this->currentUser->getId())->getOne();
        if(!$this->school) VSRedirect::to('school/pending');

    }

    public function pending()
    {
        $this->view->render('Tiimedu/School/pending');
    }

    public function index()
    {
        $this->view->render('Tiimedu/School/index',[
            'school' => $this->school
        ]);
    }

    public function admission()
    {
        $this->view->render('Tiimedu/School/admission');
    }

    public function candidate()
    {
        $this->view->render('Tiimedu/School/candidate');
    }

    public function candidateDetail()
    {
        $this->view->render('Tiimedu/School/candidate.detail');
    }


    public function candidateVisit()
    {
        $this->view->render('Tiimedu/School/candidate.visit');
    }
    


}