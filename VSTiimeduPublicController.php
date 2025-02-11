<?php
/**
 * Class VSTiimeduPublicController
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduPublicController extends VSControllerPublic
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
    }


    public function student()
    {
        require 'StudentController.php';
        $student = StudentController::getInstance();
        $action = VSRequest::vs(2) ?? 'index';
        if(method_exists($student, $action))
        {
            $student->$action();
        } else {
            $this->error404();
        }
    }


    public function school()
    {
        require 'SchoolController.php';
        $school = SchoolController::getInstance();
        $action = VSRequest::vs(2) ?? 'index';
        if(method_exists($school, $action))
        {
            $school->$action();
        } else {
            $this->error404();
        }
    }

}