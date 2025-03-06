<?php
/**
 * Class VSTiimeduPublicController
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
require_once "traitCommonModel.php";
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
        $this->modelTiimedu = VSModel::getInstance()->load('Tiimedu');
        $this->modelTiimeduUser = VSModel::getInstance()->load($this->modelTiimedu, 'Users/');
        $this->modelStudent = VSModel::getInstance()->load($this->modelTiimedu, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->modelTiimedu, 'School/');

        $this->modelUser = VSModel::getInstance()->load('User');
        $this->user = $this->modelUser->getLoggedIn();
        if($this->user)
        {
            $this->user->role = $this->modelTiimeduUser->getByUserId($this->user->getId());
            $this->user->student = $this->modelStudent->getByUserId($this->user->getId());
        }
    }

    public function index()
    {
        $this->requiredLogin();
        $role = $this->user->role->getType();
        $page = $this->user->role::TYPE[$role];
        VSRedirect::to(BASE_URL. 'tiimedu/'. $page);
    }

    public function checkPermission()
    {
        $page = VSRequest::vs(1);
        $role = $this->user->role->getType();
        $allow =  $this->user->role::TYPE[$role];
        if($page != $allow && $page != 'index')
        {
           $this->error404();
        }
    }
    public function requiredLogin()
    {
        if($this->user == false)  
        {
            if(VSRequest::vs(2) == 'checkin' && VSRequest::get('school'))
            {
                $returnUrl = BASE_URL . 'tiimedu/student/checkin?school='. VSRequest::get('school');
            }
            VSSession::set('login_return', $returnUrl ?? VSRequest::referrer());
            $this->setErrors('Bạn phải đăng nhập để thực hiện chức năng yêu cầu');
            VSRedirect::to('user/login');
        }
        $this->checkPermission();
        return $this->user;
    }

    public function postStudent()
    {
        $this->student();
    }
    public function student()
    {   
        $this->requiredLogin();
        require 'Controller/StudentController.php';
        $student = StudentController::getInstance();
        $student->user = $this->user;
        $student->model = $this->modelTiimedu;
        $student->modelTiimeduUser = $this->modelTiimeduUser;
        $student->modelUser = $this->modelUser;
        $student->modelStudent = $this->modelStudent;
        $action = VSRequest::vs(2) ?? 'index';
        if(VSRequest::isPOST())
        {
            $action = 'post'. ucfirst($action);
        }
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
        require 'Controller/SchoolController.php';
        $school = SchoolController::getInstance();
        $action = VSRequest::vs(2) ?? 'index';
        if(VSRequest::isPOST())
        {
            $action = 'post'. ucfirst($action);
        }
        if(method_exists($school, $action))
        {
            $school->$action();
        } else {
            $this->error404();
        }
    }
    public function postSchool()
    {
        $this->school();
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