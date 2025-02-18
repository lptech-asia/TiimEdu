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
    public $student = false;
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
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelGemini = VSModel::getInstance()->load($this->model, 'Gemini/');
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
        $vars = [
            'student' => $this->user->student
        ];

        $this->view->render('Tiimedu/Student/edit', $vars);
    }

    public function postEdit()
    {
        $data = $this->request->post();
    }

    public function postUpdateUser()
    {
        $file = $this->request->files('avatar');
        $result = [
            'status' => true,
            'message' => $this->lang->t('user_update_error', 'Cập nhật thông tin tài khoản không thành công.')
        ];
        if($file)
        {
            $postData['avatar']     = $this->model->processFileUpload($file, VSSetting::s('user_avatar_folder_id', '242'));
            $result['message']      = $this->lang->t('user_update_avatar_error', 'Cập nhật ảnh đại diện thành công.');
        } else {
            $postData = $this->request->post();
        }
        
        try {
            $this->modelUser->edit($this->user->getId(), $postData);
        } catch (VSException $e) 
        {
            $result['message'] = $e->message();
            $result['status'] = false;
        }
        VSJson::response($result);
    }

    public function postUpdateIdentify()
    {
        $type = $this->request->post('name');
        $ocr = $this->request->post('ocr') ?? false;
        $file = $this->request->files($type);
        $result = [
            'status' => true,
            'message' => $this->lang->t('user_update_error', 'Cập nhật thông tin tài khoản không thành công.')
        ];
        if($file)
        {
            $postData[$type.'_image']     = $this->model->processFileUpload($file, VSSetting::s('user_avatar_folder_id', '242'));
            $result['message']      = $this->lang->t('user_update_avatar_error', 'Cập nhật ảnh đại diện thành công.');
        } else {
            $postData = $this->request->post();
        }
        
        try {
            if($ocr)
            {
                // ocr by gemini
                $identifyData = $this->modelGemini->readIdentifyCard($postData[$type.'_image']);
                if($identifyData)
                {
                    $postData['identify_name'] = $identifyData->full_name ?? '';
                    $postData['date_of_birth'] = VSDateTime::parseInputDate($identifyData->dob) ?? '';
                    $postData['gender'] = ($identifyData->gender == 'Nữ' ? 2 : 1)  ?? 3;
                    $postData['identify_address'] = $identifyData->contact_address  ?? '';
                    $postData['identify_number'] = $identifyData->id_number  ?? '';
                    $result['data'] = $postData;
                }
            }
            $this->modelStudent->edit($this->user->student->getId(), $postData);
        } catch (VSException $e) 
        {
            $result['message'] = $e->message();
            $result['status'] = false;
        }
        VSJson::response($result);
    }


    public function postUpdateFieldProfile()
    {
        $data = $this->request->post();
        ddd($data);
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