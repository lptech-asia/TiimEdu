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
        $this->admin          = VSAdmin::getInstance()->getAuthenticatedAdmin();
        $this->modelMasterUser = VSModel::getInstance()->load('User');
        $this->model = VSModel::getInstance()->load($this);
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelConversations = VSModel::getInstance()->load($this->model, 'Conversations/');
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/')->addModel('modelUser', $this->modelMasterUser);
        $this->modelProgram = VSModel::getInstance()->load($this->modelSchool, 'SchoolPrograms');
        $this->modelLiving = VSModel::getInstance()->load($this->modelSchool, 'SchoolLiving');
        $this->modelScholarships = VSModel::getInstance()->load($this->modelSchool, 'SchoolScholarships');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries');

        $this->modelDocument = VSModel::getInstance()->load($this->model, 'Documents/');
        $this->modelDocumentType = VSModel::getInstance()->load($this->modelDocument, 'DocumentsType')->addModel('modelDocument', $this->modelDocument);
        $this->modelDocument->addModel('modelDocumentType', $this->modelDocumentType);
        $this->modelApplications = VSModel::getInstance()->load($this->modelStudent, 'StudentApplications')
            ->addModel('modelMasterUser', $this->modelMasterUser)
            ->addModel('modelSchool', $this->modelSchool)
            ->addModel('modelProgram', $this->modelProgram)
            ->addModel('modelScholarship', $this->modelScholarships)
            ->addModel('modelConversations', $this->modelConversations);
        $this->modelEvents = VSModel::getInstance()->load($this->model, 'Event/')
            ->addModel('modelUser', $this->modelUser)
            ->addModel('modelSchool', $this->modelSchool);
    }
    private function __setStatus($model = null, $status = 0)
    {
        if(!$model) $this->error404();
        try {
            $itemId       = $this->request->get('item_id');
            $result = array(
                'status'  => true,
                'message' => $this->lang->t('global_disable_success', "Chuyển sang trạng thái vô hiệu hóa thành công"),
            );
            $model->updateField($itemId, 'status', $status);

            if ($status == 1) {
                $result['message'] = $this->lang->t('global_enable_success', "Chuyển sang trạng thái kích hoạt thành công");
            }
        } catch (VSException $e) {
            $result['status']  = false;
            $result['message'] = $e->message();
        }

        VSJson::response($result);
    }
    
    private function __deleteItem($model = null, $id = null)
    {
        if(!$id || !$model) $this->error404();
        $delete = $model->delete($id);
        if($delete)
        {
            $this->setMessage('Xoá thành công');
        }
        VSRedirect::to($this->request->get('back'));
    }

    protected function documentStatus()
    {
        $status = $this->request->vs(2) ?? 0;
        $this->__setStatus($this->modelDocumentType, $status);
    }

    public function index() 
    {
        $this->view->render('Backend/index');
    }

    public function students() 
    {
        $students = $this->modelUser->getUser(1);
        $this->view->render('Backend/Students/index', [
            'students' => $students,
            'paging'    => $this->modelUser->getPagingElements()
        ]);
    }

    public function student() 
    {
        try {
            $user = $this->modelMasterUser->getItem($this->request->vs(2));
            if(!$user) $this->error404();
            $student = $this->modelStudent->where('user_id',$user->getId())->getOne();
            $applications = $this->modelApplications->where('user_id', $user->getId())->getPagination();
            // ddd($applications);
            $documents = $this->modelDocument->where('user_id', $user->getId())->getAll();
            // ddd($documents);
            $this->view->render('Backend/Students/detail', [
                'student' => $student,
                'user'     => $user,
                'applications' => $applications,
                'documents' => $documents,
                'paging'    => $this->modelApplications->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }

    public function postCreateUserSchool()
    {
        $postData = $this->request->post();

        // Set to female
        if (isset($postData['gender']) && $postData['gender'] != 1) {
            $postData['gender'] = 2;
        }
        // Compile validation rules
        $valid = array(
            'email'            => 'required|valid_email',
            'school_id'        => 'required',
        );

        if (isset($postData['name'])) {
            $valid['name'] = 'required';
            $postData['first_name'] = VSString::getLast($postData['name'], " ");
            $postData['last_name']  = VSString::removeLast($postData['name'], " ");
        }

        $postData = $this->validate->run($postData, $valid);
        $errors   = $this->validate->getErrors($this);

        if ($this->modelMasterUser->checkExistEmail($this->request->post('email'))) {
            $errors[] = $this->lang->t('user_register_error_email_duplicated', 'Email này đã được sử dụng.');
        }
        // add to school
        $school = $this->modelSchool->getItem($postData['school_id']);
        if($school->getUserId())
        {
            $errors[] = "Trường <strong>{$school->getName()}</strong> đã được gán cho người dùng <strong>{$school->getUser()->getEmail()}</strong> khác";
        }
        // Validation fail, save data and error messages to session.
        if (!empty($errors)) {
            $this->setErrors($errors);
            VSSessionFlash::set('register_data', $this->request->post());
            VSSessionFlash::set('register_error_message', $errors);
            VSRedirect::to(VSRequest::referrer());
        }


        $postData['password'] = VSPassword::hash($postData['password'] ?? VSString::random(16));
        $postData['status'] = 1;
        $postData['token'] = VSString::random(64);

        try {
            $userId = $this->modelMasterUser->add($postData);
            // add to tiim user
            $this->modelUser->add(['user_id' => $userId, 'type' => 2]);
            // add to school
            $this->modelSchool->edit($postData['school_id'], ['user_id' => $userId]);
            // ddd($userId);
        } catch (VSException $e) {
            $this->setErrors($e->message());
            VSSession::set('register_data', $this->request->post());
            VSSession::set('register_error_message', $e->message());
        }
        $this->setMessage($this->lang->t('user_register_success', 'Đăng ký tài khoản thành công.'), $this->lang->t('user_congratulation', 'Xin chúc mừng!'));
        VSRedirect::to('tiimedu/schools');
    }

    public function schools() 
    {
        $schools = $this->modelUser->getUser(2);
        $this->view->render('Backend/Schools/index', [
            'schools' => $schools,
            'paging'    => $this->modelUser->getPagingElements()
        ]);
    }

    public function school() 
    {
        try {
            $user = $this->modelMasterUser->getItem($this->request->vs(2));
            $school = $this->modelSchool->getByUserId($user->getId());
            $this->view->render('Backend/Schools/detail', [
                'school' => $school,
                'user'   => $user,
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }

    public function postLiveSearchUniversity()
    {
        $params['keyword'] = $this->request->post('q');
        $error = false;
        $results = [];
        try {
            if (empty($params)) {
                throw new VSException("Không có giá trị tìm kiếm nào được yêu cầu", 'hotel_search_empty_parameter');
            }
            if (isset($params['keyword'])) {
                if (VSString::length($params['keyword']) < 3) {
                    throw new VSException("Bạn phải nhập vào ít nhất 3 ký tự để tìm kiếm", 'hotel_search_short_title');
                }

                $title = htmlentities($params['keyword']);
                unset($params['keyword']);
                // Find location provinces
                $schools = $this->modelSchool->setOperator('OR')->searchLike(['name' => $title, 'sku' => $title]);
                foreach($schools as $school)
                {
                    $results[] = [
                        'id' => $school->getId(),
                        'name' => $school->getName(),
                        'sku'   => $school->getSku()
                    ];
                }
            }
        } catch (VSException $msg) {
            $error = true;
        }
        VSResponse::json(array('error' => $error, 'data' => $results));
    }

    public function postAddSchoolToUser()
    {
        $error = false;
        $data = $this->request->post();
        if($data['school_id'] && $data['user_id'])
        {
            $school = $this->modelSchool->getItem($data['school_id']);
            if($school->getUserId())
            {
                $error = true;
                $this->setErrors("Trường <strong>{$school->getName()}</strong> đã được gán cho người dùng <strong>{$school->getUser()->getEmail()}</strong> khác");
            }
            
            if(!$error)
            {
                // remove old school
                $this->modelSchool->where('user_id', $data['user_id'])->setNull('user_id');
                // add new school    
                $this->modelSchool->edit($data['school_id'], ['user_id' => $data['user_id']]);
                $this->setMessage("Trường <strong>{$school->getName()}</strong> đã được gán thành công!");

            }
        }
        VSRedirect::to('tiimedu/school/' . $data['user_id']);
    }


    public function countries()
    {
        $countries = $this->modelCountry->getAll();
        $this->view->render('Backend/countries', [
            'countries' => $countries,
            'paging'    => $this->model->getPagingElements()
        ]);
    }

    public function deleteCountry()
    {
        $this->__deleteItem($this->modelCountry, $this->request->vs(2));
    }

    // list of universities
    public function universities()
    {
        $universities = $this->modelSchool->getAll();
        $this->view->render('Backend/Universities/index', [
            'universities'  => $universities,
        ]);
    }

    public function postImportUniversities()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '0');
        // delete all living option
        $this->modelLiving->reset();
        try {
            $file = $this->request->files('import_file');
            $excel =  VSFile::loadExcel($file['tmp_name']);
            $sheet = $excel->getSheet(0);
            $countRows = $sheet->getHighestDataRow();
            $errors = [];
            for ($i = 2; $i <= $countRows; ++$i) {
                $sku = $sheet->getCell('A' . $i)->getValue();
                if (!$sku) {
                    continue;
                }
                $data = [
                    'sku'                           => $sku,
                    'name'                          => $sheet->getCell('B' . $i)->getValue(),
                    'lastest_qs_ranking'            => $sheet->getCell('C' . $i)->getValue(),
                    'lastest_shanghai_ranking'      => $sheet->getCell('D' . $i)->getValue(),
                    'lastest_national_ranking'      => $sheet->getCell('E' . $i)->getValue(),
                    'description'                   => VSString::doQuote($sheet->getCell('F' . $i)->getValue()),
                    'city'                          => $sheet->getCell('H' . $i)->getValue(),
                    'address'                       => $sheet->getCell('I' . $i)->getValue(),
                    'found_year'                    => $sheet->getCell('J' . $i)->getValue(),
                    'type'                          => $sheet->getCell('K' . $i)->getValue(),
                    'enrollment_email'              => VSString::doQuote($sheet->getCell('L' . $i)->getValue()),
                    'accountant_email'              => VSString::doQuote($sheet->getCell('M' . $i)->getValue()),
                    'management_email'              => VSString::doQuote($sheet->getCell('N' . $i)->getValue()),
                    'brochure'                      => VSString::doQuote($sheet->getCell('O' . $i)->getValue()),
                    'image_folder'                  => $sheet->getCell('P' . $i)->getValue(),
                    'campus_residence'              => VSString::doQuote($sheet->getCell('Q' . $i)->getValue())

                ];
                // Process detect and get Country ID
                $countryCode =  $sheet->getCell('G' . $i)->getValue();
                $country = $this->modelCountry->isExist('name', $countryCode);
                if ($country) {
                    $data['country_id'] = $country->getId();
                } else {
                    $country_id = $this->modelCountry->add(['name' => $countryCode]);
                    $data['country_id'] = $country_id;
                }
                // Process add Living option
                $livingOption = [
                    VSString::doQuote($sheet->getCell('R' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('S' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('T' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('U' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('V' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('W' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('X' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('Y' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('Z' . $i)->getValue()),
                    VSString::doQuote($sheet->getCell('AA' . $i)->getValue())
                ];
                $university = $this->modelSchool->isExist('sku', $data['sku']);
                if ($university) {
                    $data['updated_by']    = intval($this->admin->getId());
                    $this->modelSchool->edit($university->getId(), $data);
                    $uniId = $university->getId();
                } else {
                    $data['status'] = 1;
                    $data['updated_by']    = intval($this->admin->getId());
                    if(!empty($data))
                    {
                        $uniId = $this->modelSchool->add($data);
                    }
                }
                // add Living option
                foreach($livingOption as $option) {
                    if (!empty($option)) {
                        $this->modelLiving->add([
                            'school_id' => $uniId,
                            'content'      => $option
                        ]);
                    }
                }
                $this->setMessage('Import dữ liệu trường đại học thành công');
            }
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
        }
        VSRedirect::to('tiimedu/universities');
    }

    public function postImportPrograms()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '0');
        // delete all scholarships option
        $this->modelScholarships->reset();
        try {
            $file = $this->request->files('import_file');
            $excel =  VSFile::loadExcel($file['tmp_name']);
            $sheet = $excel->getSheet(0);
            $countRows = $sheet->getHighestDataRow();
            $errors = [];
            
            for ($i = 2; $i <= $countRows; ++$i) {
                $sku = $sheet->getCell('A' . $i)->getValue();
                if (!$sku) {
                    continue;
                }
                // find university
                $university = $this->modelSchool->isExist('sku', $sku);
                if (!$university) {
                    continue;
                }
                $data = [
                    'school_id'                                 => $university->getId(),
                    'university_name'                           => VSString::doQuote($sheet->getCell('B' . $i)->getValue()),
                    'program_id'                                => $sheet->getCell('C' . $i)->getValue(),
                    'degree'                                    => $sheet->getCell('D' . $i)->getValue(),
                    'program_name'                              => VSString::doQuote($sheet->getCell('E' . $i)->getValue()),
                    'duration'                                  => $sheet->getCell('F' . $i)->getValue(),
                    'fee_year1'                                 => $sheet->getCell('G' . $i)->getValue(),
                    'fee_year2'                                 => $sheet->getCell('H' . $i)->getValue(),
                    'fee_year3'                                 => $sheet->getCell('I' . $i)->getValue(),
                    'fee_year4'                                 => $sheet->getCell('J' . $i)->getValue(),
                    'fee_year5'                                 => $sheet->getCell('K' . $i)->getValue(),
                    'fee_year6'                                 => $sheet->getCell('L' . $i)->getValue(),
                    'whole_program_fee'                         => VSString::doQuote($sheet->getCell('M' . $i)->getValue()),
                    'additional_fee'                            => VSString::doQuote($sheet->getCell('N' . $i)->getValue()),
                    'language_required'                         => VSString::doQuote($sheet->getCell('O' . $i)->getValue()),
                    'gpa_required'                              => VSString::doQuote($sheet->getCell('P' . $i)->getValue()),
                    'cv_motivation_letter'                      => VSString::doQuote($sheet->getCell('Q' . $i)->getValue()),
                    'referent_letter'                           => $sheet->getCell('R' . $i)->getValue(),
                    'interview'                                 => $sheet->getCell('S' . $i)->getValue(),
                    'finance_proof'                             => $sheet->getCell('T' . $i)->getValue(),
                    'intake1'                                   => $sheet->getCell('U' . $i)->getValue(),
                    'deadline1'                                 => $sheet->getCell('V' . $i)->getValue(),
                    'intake2'                                   => $sheet->getCell('W' . $i)->getValue(),
                    'deadline2'                                 => $sheet->getCell('X' . $i)->getValue(),
                    'intake3'                                   => $sheet->getCell('Y' . $i)->getValue(),
                    'deadline3'                                 => $sheet->getCell('Z' . $i)->getValue(),
                    'acceptance_rate'                           => $sheet->getCell('AA' . $i)->getValue(),
                    'international_students'                    => $sheet->getCell('AB' . $i)->getValue(),
                    'updated_by'                                => intval($this->admin->getId())    
                ];
                $scholarships = [
                    $sheet->getCell('AC' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AC' . $i)->getValue()),
                    $sheet->getCell('AD' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AD' . $i)->getValue()),
                    $sheet->getCell('AE' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AE' . $i)->getValue()),
                    $sheet->getCell('AF' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AF' . $i)->getValue()),
                    $sheet->getCell('AG' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AG' . $i)->getValue()),
                    $sheet->getCell('AH' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AH' . $i)->getValue()),
                    $sheet->getCell('AI' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AI' . $i)->getValue()),
                    $sheet->getCell('AJ' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AJ' . $i)->getValue()),
                    $sheet->getCell('AK' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AK' . $i)->getValue()),
                    $sheet->getCell('AL' . 1)->getValue()       => VSString::doQuote($sheet->getCell('AL' . $i)->getValue())
                ];
                $program = $this->modelProgram->isExist('program_id', $data['program_id']);
                if ($program) 
                {
                    $programId = $program->getId();
                    $this->modelProgram->edit($programId, $data);
                } else {
                    $data['status'] = 1;
                    if(!empty($data))
                    {
                        $programId = $this->modelProgram->add($data);
                    }
                }
                // add scholarship option
                foreach($scholarships as $name => $sholarship) 
                {
                    if (!empty($sholarship)) {
                        $this->modelScholarships->add([
                            'program_id'    => $programId,
                            'description'   => $sholarship,
                            'name'          => $name
                        ]);
                    }
                }
                $this->setMessage('Import dữ liệu chương trình đào tạo thành công');
            }
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
        }
        VSRedirect::to('tiimedu/universities');
    }
    
    // detail of university
    public function university()
    {
        try {
            $university = $this->modelSchool->getItem($this->request->vs(2));
            $countProgram = $this->modelProgram->where('school_id', $university->getId())->countItem();
            $countApplication = $this->modelApplications->where('school_id', $university->getId())->countItem();
            $user = false;
            if($university && $university->getUserId())
            {
                $user = $this->modelMasterUser->getItem($university->getUserId());
            }
            $this->view->render('Backend/Universities/detail', [
                'university' => $university,
                'user'       => $user,
                'countProgram' => $countProgram,
                'countApplication' => $countApplication
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }

    // detail of university
    public function program()
    {
        try {
            $university = $this->modelSchool->getItem($this->request->vs(2));
            $programs   =  $this->modelProgram->where('school_id', $university->getId())->getPagination();
            $countProgram = $this->modelProgram->where('school_id', $university->getId())->countItem();
            $countApplication = $this->modelApplications->where('school_id', $university->getId())->countItem();
            $this->view->render('Backend/Universities/program', [
                'university' => $university,
                'programs'   => $programs,
                'countProgram' => $countProgram,
                'countApplication' => $countApplication,
                'paging'    => $this->modelProgram->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }

    public function candidate()
    {
        try {
            $university = $this->modelSchool->getItem($this->request->vs(2));
            $countProgram = $this->modelProgram->where('school_id', $university->getId())->countItem();
            $countApplication = $this->modelApplications->where('school_id', $university->getId())->countItem();
            $applications = $this->modelApplications->where('school_id', $university->getId())->getPagination();
            $this->view->render('Backend/Universities/candidate', [
                'university' => $university,
                'countProgram' => $countProgram,
                'countApplication' => $countApplication,
                'applications' => $applications,
                'paging'    => $this->modelApplications->getPagingElements()
            ]);
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
            $this->error404();
        }
    }

    public function postUniversity()
    {
        $data = $this->request->post();
        $id = $data['id'] ?? false;
        unset($data['id']);
        try {
            $this->modelSchool->edit($id, $data);
            $this->setMessage('Cập nhật thông tin trường thành công');
        } catch (VSException $e) {
            $this->setErrors($e->getMessage());
        }
        VSRedirect::to('tiimedu/university/' . $id);
    }

    // detail of documents
    public function documents()
    {
        $documentsTypes = $this->modelDocumentType->getAll();
        $this->view->render('Backend/Documents/index', [
            'documentsTypes' => $documentsTypes
        ]);
    }

    // post create docs
    public function postDocumentCreate()
    {
        $data = $this->request->post();
        $edit = $this->request->post('id') ?? false;
        $data = $this->validate->run($data, [
            'name' => 'required',
            'limit' => 'required'
        ]);
        if ($data === false) {
            $errors = $this->validate->getErrors($this);
        }

        if (empty($errors)) 
        {
            if($edit)
            {
                $this->modelDocumentType->edit($edit, $data);
                $this->setMessage('Thêm mới loại tài liệu thành công');
            } else {
                $data['status'] = 1;
                $this->modelDocumentType->add($data);
                $this->setMessage('Thêm mới loại tài liệu thành công');
            }
            
        } else {
            $this->setErrors($errors);
        }

        VSRedirect::to('tiimedu/documents');
    }

    public function deleteDocumentType()
    {
        $this->__deleteItem($this->modelDocumentType, $this->request->vs(2));
    }
    
    public function deleteUniversity()
    {
        $this->__deleteItem($this->modelSchool, $this->request->vs(2));
    }

    public function editDocumentType()
    {
        $documentsType = $this->modelDocumentType->getItem($this->request->vs(2));
        $this->view->render('Backend/Documents/edit', [
            'documentsType' => $documentsType
        ]);
    }

    public function applications()
    {
        $applications = $this->modelApplications->getPagination();
        $this->view->render('Backend/Applications/index', [
            'applications' => $applications,
            'paging'    => $this->modelApplications->getPagingElements()
        ]);
    }

    public function events()
    {
        $events = $this->modelEvents->getPagination();
        $this->view->render('Backend/events', [
            'events' => $events,
            'paging'    => $this->modelEvents->getPagingElements()
        ]);
    }
    
}