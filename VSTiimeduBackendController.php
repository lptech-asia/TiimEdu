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
        $this->model = VSModel::getInstance()->load($this);
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
        $this->modelProgram = VSModel::getInstance()->load($this->modelSchool, 'SchoolPrograms');
        $this->modelLiving = VSModel::getInstance()->load($this->modelSchool, 'SchoolLiving');
        $this->modelScholarships = VSModel::getInstance()->load($this->modelSchool, 'SchoolScholarships');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries');

        $this->modelDocument = VSModel::getInstance()->load($this->model, 'Documents/');
        $this->modelDocumentType = VSModel::getInstance()->load($this->modelDocument, 'DocumentsType')->addModel('modelDocument', $this->modelDocument);

        $this->modelMasterUser = VSModel::getInstance()->load('User');
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
        $universities = $this->modelSchool->getAll();
        $this->view->render('Backend/Universities/index', [
            'universities'  => $universities,
        ]);
    }

    public function postImportUniversities()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '0');
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
                    // delete all living option
                    $this->modelLiving->deleteBySchoolId($uniId);
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
                    $sheet->getCell('AC' . $i)->getValue(),
                    $sheet->getCell('AD' . $i)->getValue(),
                    $sheet->getCell('AE' . $i)->getValue(),
                    $sheet->getCell('AF' . $i)->getValue(),
                    $sheet->getCell('AG' . $i)->getValue(),
                    $sheet->getCell('AH' . $i)->getValue(),
                    $sheet->getCell('AI' . $i)->getValue(),
                    $sheet->getCell('AJ' . $i)->getValue(),
                    $sheet->getCell('AK' . $i)->getValue(),
                    $sheet->getCell('AL' . $i)->getValue()
                ];
                $program = $this->modelProgram->isExist('school_id', $data['school_id']);
                if ($program) 
                {
                    $programId = $program->getId();
                    $this->modelProgram->edit($programId, $data);
                    // delete all scholarships option
                    $this->modelScholarships->deleteByProgramId($programId);
                } else {
                    $data['status'] = 1;
                    if(!empty($data))
                    {
                        $programId = $this->modelProgram->add($data);
                    }
                }
                // add Living option
                foreach($scholarships as $sholarship) 
                {
                    if (!empty($sholarship)) {
                        $this->modelScholarships->add([
                            'program_id' => $programId,
                            'description'      => $sholarship
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
        $this->view->render('Backend/Universities/detail');
    }

    // detail of documents
    public function documents()
    {
        $documentsTypes = $this->modelDocumentType->getAll();
        $this->view->render('Backend/documents', [
            'documentsTypes' => $documentsTypes
        ]);
    }

    public function applications()
    {
        $this->view->render('Backend/applications');
    }

    public function events()
    {
        $this->view->render('Backend/events');
    }
    
}