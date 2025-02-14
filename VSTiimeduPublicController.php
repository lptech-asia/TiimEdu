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
    public $user = false;
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
        $this->modelUser = VSModel::getInstance()->load('User');
        $this->user = $this->modelUser->getLoggedIn();
    }

    public function requiredLogin()
    {
        if($this->user == false)  
        {
            $this->setErrors('Bạn phải đăng nhập để thực hiện chức năng yêu cầu');
            VSRedirect::to('user/login');
        }
        return $this->user;
    }



    public function student()
    {
        $this->requiredLogin();
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
        $this->requiredLogin();
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


    public function role()
    {
        $this->view->render('Tiimedu/select.role');
    }

    public function postRole()
    {
        $role = VSRequest::post('type');
        VSSession::set('role', $role);
        VSJson::response(['status' => 'success', 'message' => 'Chọn vai trò thành công']);
    }

}