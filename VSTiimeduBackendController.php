<?php
/**
 * Class VSTiimeduBackendController
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduBackendController extends VSControllerBackend
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
        $this->model = VSModel::getInstance()->load($this);
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries');

        $this->modelMasterUser = VSModel::getInstance()->load('User');
    }
    public function index() 
    {
        $this->view->render('Backend/index');
    }

    public function students() 
    {
        $students = $this->modelUser->getUser(1);
        $this->view->render('Backend/students', [
            'students' => $students,
            'paging'    => $this->modelUser->getPagingElements()
        ]);
    }

    public function school() 
    {
        $schools = $this->modelUser->getUser(2);
        $this->view->render('Backend/schools', [
            'schools' => $schools,
            'paging'    => $this->modelUser->getPagingElements()
        ]);
    }

    public function countries()
    {
        $countries = $this->modelCountry->getAll();
        $this->view->render('Backend/countries', [
            'countries' => $countries,
            'paging'    => $this->model->getPagingElements()
        ]);
    }

    // list of universities
    public function universities()
    {
        $this->view->render('Backend/universities');
    }
    
    // detail of university
    public function university()
    {
        $this->view->render('Backend/university');
    }
    
}