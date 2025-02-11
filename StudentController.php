<?php
/**
 * Class VSTiimeduPublicController
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class StudentController extends VSControllerPublic
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
        // $this->model = VSModel::getInstance()->load($this);
    }

    public function index()
    {
        $this->view->render('Tiimedu/Student/index');
    }
    
    // event / module checkin
    public function checkin()
    {
        $this->view->render('Tiimedu/Student/checkin');
    }


    public function account() 
    {
        $this->view->render('Tiimedu/Student/account');
    }
    

    public function edit()
    {
        $this->view->render('Tiimedu/Student/edit');
    }


    public function suggestion()
    {
        $this->view->render('Tiimedu/Student/suggestions');
    }

    public function school()
    {
        $this->view->render('Tiimedu/Student/school');
    }

    public function countries()
    {
        $this->view->render('Tiimedu/Student/countries');
    }

    public function country()
    {
        $this->view->render('Tiimedu/Student/country');
    }
    
    public function apply()
    {
        $this->view->render('Tiimedu/Student/apply');
    }

    public function viewed()
    {
        $this->view->render('Tiimedu/Student/viewed');
    }

    public function applicants()
    {
        $this->view->render('Tiimedu/Student/applicants');
    }

    public function applicantDetail()
    {
        $this->view->render('Tiimedu/Student/applicants.detail');
    }


}