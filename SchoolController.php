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
        // $this->model = VSModel::getInstance()->load($this);
    }

    public function index()
    {
        $this->view->render('Tiimedu/School/index');
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