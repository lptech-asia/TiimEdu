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
        $this->modelMasterUser = VSModel::getInstance()->load('User');
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
        $this->modelGemini = VSModel::getInstance()->load($this->model, 'Gemini/');
        $this->modelDocument = VSModel::getInstance()->load($this->model, 'Documents/');
        $this->modelEvent = VSModel::getInstance()->load($this->model, 'Event/');
        $this->modelConversations = VSModel::getInstance()->load($this->model, 'Conversations/')
            ->addModel('modelUser', $this->modelMasterUser);
        ####### LOAD MASTER MODEL ABOVE #######
        $this->modelDocumentType = VSModel::getInstance()->load($this->modelDocument, 'DocumentsType')
            ->addModel('modelDocument', $this->modelDocument);
        $this->modelDocument
            ->addModel('modelDocumentType', $this->modelDocumentType);
        
        $this->modelProgram = VSModel::getInstance()->load($this->modelSchool, 'SchoolPrograms');
        $this->modelLiving = VSModel::getInstance()->load($this->modelSchool, 'SchoolLiving');
        $this->modelScholarships = VSModel::getInstance()->load($this->modelSchool, 'SchoolScholarships');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries')
            ->addModel('modelSchool', $this->modelSchool);
        $this->modelSchool
            ->addModel('modelProgram', $this->modelProgram)
            ->addModel('modelCountry', $this->modelCountry);
        $this->modelViewed = VSModel::getInstance()->load($this->modelStudent, 'StudentViewed')
            ->addModel('modelSchool', $this->modelSchool)->addModel('modelProgram', $this->modelProgram);
        $this->modelApplication = VSModel::getInstance()->load($this->modelStudent, 'StudentApplications')
            ->addModel('modelMasterUser', $this->modelMasterUser)
            ->addModel('modelScholarship', $this->modelScholarships)
            ->addModel('modelProgram', $this->modelProgram)
            ->addModel('modelSchool', $this->modelSchool);
        $this->modelProgram->addModel('modelApplication', $this->modelApplication);
    }

    public function index()
    {
        $this->view->render('Tiimedu/Student/index');
    }
    
    // event / module checkin
    public function checkin()
    {
        $encode = $this->request->get('school') ?? null;
        $sku = base64_decode($encode);
        $school = $this->modelSchool->where('sku', $sku)->getOne();
        $allowChecked = $this->modelEvent->where('user_id', $this->user->getId())->where('school_id', $school->getId())->getOne();
        $this->view->render('Tiimedu/Student/checkin', [
            'school' => $school,
            'checked' => $allowChecked
        ]);
    }

    public function postCheckin()
    {
        try {
            $data = $this->request->post();
            $data['user_id'] = $this->user->getId();
            $exist = $this->modelEvent->where('user_id', $data['user_id'])->where('school_id', $data['school_id'])->countItem();
            if($exist > 0)
            {
                $this->setErrors($this->lang->t('tiimedu_student_checkin_error', 'Bạn đã checkin trường này rồi.'));
            } else {
                $this->modelEvent->add($data);
                $this->setSuccess($this->lang->t('tiimedu_student_checkin_success', 'Checkin thành công.'));
            }
        } catch (VSException $e) {
            $this->setErrors($e->message());
        }
        VSRedirect::to(VSRequest::referrer());
    }


    public function account() 
    {
        $vars = [
            'student' => $this->user->student,
            'documentTypes' => $this->modelDocumentType->where('status',1)->getAll()
        ];
        $this->view->render('Tiimedu/Student/account', $vars);
    }
    

    public function edit()
    {
        $vars = [
            'student' => $this->user->student,
            'documentTypes' => $this->modelDocumentType->where('status',1)->getAll()
        ];
        $this->view->render('Tiimedu/Student/edit', $vars);
    }

    public function postDeleteDocument()
    {
        $itemId = $this->request->post('id');
        $result = [
            'status' => false,
            'message' => $this->lang->t('tiimedu_student_delete_docs_error', 'Lỗi. Không thể xóa tài liệu.')
        ];
        try {
            $this->modelDocument->deleteItem($itemId);
            $result['message'] = "Xóa tài liệu thành công.";
            $result['status'] = true;
        } catch (VSException $e)  {
            $result['message'] = $e->message();
            $result['status'] = false;
        }
        VSJson::response($result);
    }

    public function postUpdateUser()
    {
        $file = $this->request->files('avatar');
        $result = [
            'status' => true,
            'message' => $this->lang->t('user_update_error', 'Cập nhật thông tin tài khoản thành công.')
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
        $read_id = $this->request->post('read_id') ?? false;
        $read_pp = $this->request->post('read_pp') ?? false;
        $file = $this->request->files($type);
        $result = [
            'status' => true,
            'message' => $this->lang->t('user_update_error', 'Cập nhật thông tin tài khoản thành công.')
        ];
        if($file)
        {
            $postData[$type]     = $this->model->processFileUpload($file, VSSetting::s('user_avatar_folder_id', '242'));
            $result['message']      = $this->lang->t('user_update_avatar_error', 'Cập nhật ảnh đại diện thành công.');
        } else {
            $postData = $this->request->post();
        }
        
        try {
            if($read_id)
            {
                // ocr by gemini
                $identifyData = $this->modelGemini->readIdentifyCard($postData[$type]);
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
            if($read_pp)
            {
                // ocr by gemini
                $passportData = $this->modelGemini->readPassport($postData[$type]);
                if($passportData)
                {
                    $postData['passport_name'] = $passportData->full_name ?? '';
                    $postData['passport_expired_at'] = VSDateTime::parseInputDate($passportData->date_of_expiry) ?? '';
                    $postData['passport_issue_at'] = VSDateTime::parseInputDate($passportData->date_of_issue) ?? '';
                    $postData['passport_nationality'] = $passportData->nationality  ?? '';
                    $postData['passport_number'] = $passportData->passport_number  ?? '';
                    $postData['passport_id_card'] = $passportData->id_card_number  ?? '';
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


    public function postUpdateDocument()
    {
        $file = $this->request->files('file');
        $result = [
            'status' => false,
            'message' => $this->lang->t('tiimedu_student_upload_docs_error', 'Lỗi. Không thể Cập nhật tài liệu.')
        ];
        if($file)
        {
            $postData['file']     = $this->model->processFileUpload($file, VSSetting::s('user_avatar_folder_id', '242'));
            if(!$postData['file'])
            {
                $result['message'] = $this->lang->t('tiimedu_student_upload_docs_error', 'Lỗi. Không thể Cập nhật tài liệu.');
                VSJson::response($result);
                die;
            }
            $postData['type_id'] = $this->request->post('type_id');
            // user_id
            $postData['user_id'] = $this->user->getId();
            // name
            $postData['name']  = VSFile::filterFileName($file['name']);
            $postData['file_extension'] = pathinfo($file['name'], PATHINFO_EXTENSION);
            $postData['file_size'] = $file['size'];
            try {
                $this->modelDocument->add($postData);
                $result['message'] = "Cập nhật tài liệu thành công.";
                $result['status'] = true;
            } catch (VSException $e)  {
                $result['message'] = $e->message();
                $result['status'] = false;
            }
        }
        
        VSJson::response($result);
    }

    

    public function suggestion()
    {
        $this->view->render('Tiimedu/Student/suggestions');
    }

    public function school()
    {
        try {
            $university = $this->modelSchool->getItem($this->request->vs(3));
            $livingOption = $this->modelLiving->where('school_id', $university->getId())->getAll();
            $programs = $this->modelProgram->where('school_id', $university->getId())->getPagination();
            // add viewed
            $this->modelViewed->addViewed($this->user->getId(), $university->getId());
            $this->view->render('Tiimedu/Student/school', [
                'university' => $university,
                'livingOption' => $livingOption,
                'programs' => $programs,
                'paging' => $this->modelProgram->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->message());
            $this->error404();
        }
    }

    public function countries()
    {
        $keyword = $this->request->get('country') ?? null;
        if($keyword)
        {
            $countries = $this->modelCountry->searchLike(['name' => $keyword]);
        } else {
            $countries = $this->modelCountry->getPagination();
        }
        $vars = [
            'countries' => $countries,
            'paging' => $this->modelCountry->getPagingElements()
        ];
        $this->view->render('Tiimedu/Student/countries', $vars);
    }

    public function admission()
    {
        $program = $this->modelProgram->getItem($this->request->vs(3));
        $university = $this->modelSchool->getItem($program->getSchoolId());
        $schoolarships = $this->modelScholarships->where('program_id', $program->getId())->getAll();
        $this->view->render('Tiimedu/Student/admission.detail', [
            'program'   => $program,
            'university' => $university,
            'schoolarships' => $schoolarships
        ]);
    }

    public function country()
    {
        try {
            $country = $this->modelCountry->getItem($this->request->vs(3));
            // get school
            $schools = $this->modelSchool->where('country_id', $country->getId())->getPagination();
            $this->view->render('Tiimedu/Student/country', [
                'country' => $country,
                'universities' => $schools,
                'paging' => $this->modelSchool->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->message());
            $this->error404();
        }
    }
    public function apply()
    {
        try {
            $vars = [
                'student' => $this->user->student
            ];
            $program = $this->modelProgram->getItem($this->request->vs(3));
            $vars['school'] = $this->modelSchool->getItem($program->getSchoolId());
            $vars['scholarships'] = $this->modelScholarships->where('program_id', $program->getId())->getAll();
            $vars['program'] = $program;
            $vars['documentTypes'] = $this->modelDocumentType->where('status',1)->getAll();
            // curent application
            $vars['applied'] = $this->modelApplication->where('user_id', $this->user->getId())->where('program_id', $program->getId())->getOne();
            $vars['allowChat'] = false;
            $this->view->render('Tiimedu/Student/apply', $vars);
        } catch (VSException $e) {
            $this->setErrors($e->message());
            $this->error404();
        }
    }

    public function postApply()
    {
        $postData = $this->request->post();
        $required = [
            'program_id' => 'required',
        ];
        if(isset($postData['accept_apply']))
        {
            $required['scholarship_id'] = 'required';
        }
        $data = $this->validate->run($postData, $required);
        if ($data === false) {
            $errors = $this->validate->getErrors($this);
        }
        $program = $this->modelProgram->getItem($postData['program_id']);
        if($program->isApplied())
        {
            $errors = $this->lang->t('tiimedu_student_apply_already_error', 'You have already applied for this program.');
        }
        
        if (empty($errors)) 
        {
            $data['status'] = 0;
            $data['user_id'] = $this->modelApplication->getLoggined()->getId();
            $data['name'] = $program->getProgramName();
            $data['school_id'] = $program->getSchoolId();
            // up load cover letter attach
            $coverLetterFile = $this->request->files('cover_letter_attachment');
            if($coverLetterFile)
            {
                $data['cover_letter_attachment']     = $this->model->processFileUpload($coverLetterFile, VSSetting::s('tiimedu_apply_attachment_folder', '243'));
            }
            $scholarshipEssayFile = $this->request->files('scholarship_essay_attachment');
            if($scholarshipEssayFile)
            {
                $data['scholarship_essay_attachment']     = $this->model->processFileUpload($scholarshipEssayFile, VSSetting::s('tiimedu_apply_attachment_folder', '243'));
            }
            $this->modelApplication->add($data);
            $this->setMessage('Apply Success');
            VSRedirect::to('tiimedu/student/applicants');
        } else {
            $this->setErrors($errors);
            VSRedirect::to(VSRequest::referrer());
        }
        
    }

    public function viewed()
    {
        $viewedUniversity = $this->modelViewed->where('user_id', $this->user->getId())->getPagination();
        // ddd($viewedUniversity);
        $this->view->render('Tiimedu/Student/viewed', [
            'viewedUniversity' => $viewedUniversity,
            'paging' => $this->modelViewed->getPagingElements()
        ]);
    }

    public function applicants()
    {
        $vars = [];
        $user = $this->user;
        $status = $this->request->vs(3) ?? 0;
        $vars['applicants'] = $this->modelApplication->where('user_id', $user->getId())->where('status', $status)->limit(12)->getPagination();
        $vars['submited'] = $this->modelApplication->where('user_id', $user->getId())->where('status', 0)->countItem();
        $vars['viewed'] = $this->modelApplication->where('user_id', $user->getId())->where('status', 1)->countItem();
        $vars['agreed'] = $this->modelApplication->where('user_id', $user->getId())->where('status', 2)->countItem();
        $vars['refused'] = $this->modelApplication->where('user_id', $user->getId())->where('status', 3)->countItem();
        $vars['paging'] = $this->modelApplication->where('user_id', $user->getId())->getPagingElements();
        $this->view->render('Tiimedu/Student/applicants', $vars);
    }

    public function applicantDetail()
    {
        try {
            $vars = [
                'student' => $this->user->student
            ];
            $applicantId = $this->request->vs(3);
            $applicant = $this->modelApplication->getItem($applicantId);
            $vars['applicant'] = $applicant;
            $vars['documents'] = $this->modelDocument->where('user_id', $applicant->getUserId())->getAll();
            $vars['documentTypes'] = $this->modelDocumentType->where('status',1)->getAll();
            if($applicant->getScholarshipId()) 
            {
                $vars['scholarship'] = $this->modelScholarships->where('id', $applicant->getScholarshipId())->getOne();
            }
            $vars['conversations'] = $this->modelConversations->where('application_id', $applicant->getId())->getAll();
            $vars['allowChat'] = true;
            $this->view->render('Tiimedu/Student/applicants.detail', $vars);
        } catch (VSException $e) {
            $this->setErrors($e->message());
            $this->error404();
        }
    } 

    public function conversation()
    {
        try {
            $applicantId = $this->request->vs(3);
            $applicant = $this->modelApplication->getItem($applicantId);
            if($applicant->getUserId() != $this->user->getId()) {
                $this->error404();
            }
            $user = $this->modelMasterUser->getItem($applicant->getUserId());
            $conversations = $this->modelConversations->where('application_id', $applicant->getId())->getPagination();
            $this->view->render('Tiimedu/School/conversation',  [
                'applicant' => $applicant,
                'user' => $user,
                'allowChat'  => true,
                'conversations' => $conversations,
                'paging' => $this->modelConversations->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }
}