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

        $this->university = $this->modelSchool->where('user_id', $this->currentUser->getId())->getOne();
        if(!$this->university) VSRedirect::to('school/pending');

    }

    public function pending()
    {
        $this->view->render('Tiimedu/School/pending');
    }

    public function index()
    {
        $university = $this->university;
        $livingOption = $this->modelLiving->where('school_id', $university->getId())->getAll();
        $programs = $this->modelProgram->where('school_id', $university->getId())->getPagination();
        $this->view->render('Tiimedu/School/index',[
            'university' => $university,
            'livingOption' => $livingOption,
            'programs' => $programs,
            'paging' => $this->modelProgram->getPagingElements()
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
    
    public function postScholarships()
    {
        $id = $this->request->post('program_id');
        $result = ['status' => true, 'message' => ''];
        try {
            $scholarships = $this->modelScholarships->where('program_id', $id)->getAll();

            foreach($scholarships as $scholarship) {
                $result['data'][] = [
                    'id' => $scholarship->getId(),
                    'name' => $scholarship->getName(),
                    'description' => $scholarship->getDescription()
                ];
            }

        }
        catch(VSException $e) {
            $result['status'] = false;
            $result['message'] = $e->message();
            VSDebug::log($e->message());
        }
        VSJson::response($result);
    }
}