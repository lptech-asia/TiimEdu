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
        $this->model = VSModel::getInstance()->load('Tiimedu');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
        $this->currentUser = $this->modelSchool->getLoggined();
        $this->university = $this->modelSchool->where('user_id', $this->currentUser->getId())->getOne();
        // if university was not assigned
        if(!$this->university)
        {
            $this->pending();
            die;
        }
        $this->modelProgram = VSModel::getInstance()->load($this->modelSchool, 'SchoolPrograms');
        $this->modelLiving = VSModel::getInstance()->load($this->modelSchool, 'SchoolLiving');
        $this->modelScholarships = VSModel::getInstance()->load($this->modelSchool, 'SchoolScholarships');
        $this->modelCountry = VSModel::getInstance()->load($this->modelSchool, 'SchoolCountries')->addModel('modelSchool', $this->modelSchool);
        $this->modelSchool->addModel('modelProgram', $this->modelProgram);
        $this->modelMasterUser = VSModel::getInstance()->load('User');
        
        $this->modelEvent = VSModel::getInstance()->load($this->model, 'Event/')->addModel('modelMasterUser', $this->modelMasterUser);
        $this->modelDocument = VSModel::getInstance()->load($this->model, 'Documents/');
        $this->modelDocumentType = VSModel::getInstance()->load($this->modelDocument, 'DocumentsType')->addModel('modelDocument', $this->modelDocument);
        $this->modelDocument->addModel('modelDocumentType', $this->modelDocumentType);
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelApplication = VSModel::getInstance()->load($this->modelStudent, 'StudentApplications')->addModel('modelMasterUser', $this->modelMasterUser);
    }

    public function pending()
    {
        $this->view->render('Tiimedu/School/pending');
    }

    public function index()
    {
        $livingOption = $this->modelLiving->where('school_id', $this->university->getId())->getAll();
        $programs = $this->modelProgram->where('school_id', $this->university->getId())->getPagination();
        $applicantsPending      = $this->modelApplication->where('school_id', $this->university->getId())->where('status', 0)->limit(VSSetting::s('tiimedu_applicants_pending_limit', 6))->getAll();

        $this->view->render('Tiimedu/School/index',[
            'university' => $this->university,
            'livingOption' => $livingOption,
            'programs' => $programs,
            'paging' => $this->modelProgram->getPagingElements(),
            'applicantsPending' => $applicantsPending
        ]);
    }

    public function admission()
    {
        $applicantsPending      = $this->modelApplication->where('school_id', $this->university->getId())->where('status', 0)->limit(VSSetting::s('tiimedu_applicants_pending_limit', 6))->getAll();
        $applicantsPendingTotal = $this->modelApplication->where('school_id', $this->university->getId())->where('status', 0)->countItem();
        $programs               = $this->modelProgram->where('school_id', $this->university->getId())->limit(VSSetting::s('tiimedu_programs_limit', 6))->getPagination();
        $programsTotal          = $this->modelProgram->where('school_id', $this->university->getId())->countItem();
        $this->view->render('Tiimedu/School/admission',[
            'applicantsPending'      => $applicantsPending,
            'applicantsPendingTotal' => $applicantsPendingTotal,
            'applicantsTotal'        => $this->modelApplication->countItem(),
            'programs'               => $programs,
            'programsTotal'          => $programsTotal,
            'paging'                 => $this->modelProgram->getPagingElements()
        ]);
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

        $totalVisitor               = $this->modelEvent->where('school_id', $this->university->getId())->countItem();
        $totalVisitorViewProfile    = $this->modelEvent->where('school_id', $this->university->getId())->where('view',1)->countItem();
        $candidates                 = $this->modelEvent->where('school_id', $this->university->getId())->getPagination();
        $checkinUrl                 = BASE_URL . 'tiimedu/student/checkin/?school=' . base64_encode($this->university->getSku());
        $qrcodeUrl                  = "https://quickchart.io/qr?text={$checkinUrl}&margin=1&size=500";
        $this->view->render('Tiimedu/School/candidate.visit', [
            'totalVisitor' => $totalVisitor,
            'totalVisitorViewProfile' => $totalVisitorViewProfile,
            'candidates' => $candidates,
            'checkinUrl' => $checkinUrl,
            'qrcodeUrl' => $qrcodeUrl,
            'paging' => $this->modelEvent->getPagingElements()
        ]);
    }
    public function studentProfile()
    {
        $userId = $this->request->vs(3);
        $candidate                 = $this->modelEvent->where('school_id', $this->university->getId())->where('user_id', $userId)->getOne();
        $student = $this->modelStudent->where('user_id', $userId)->getOne();
        if(!$candidate || !$student) {
            $this->setErrors($this->lang->t('tiimedu_view_profile_not_allow_notice','You are not allowed to view this student profile because the student may not be enrolled in your school\'s program'));
            VSRedirect::to(VSRequest::referrer());
        }
        // update status view
        if($candidate->getView() == 0)
        {
            $this->modelEvent->edit($candidate->getId(), [
                'view' => 1,
                'viewed_by' => $this->currentUser->getId()
            ]);
        }
        
        $vars = [
            'student' => $student,
            'documentTypes' => $this->modelDocumentType->where('status',1)->getAll()
        ];
        $this->view->render('Tiimedu/School/student.profile', $vars);
    }
    
    public function postScholarships()
    {
        $id = $this->request->post('program_id');
        $result = ['status' => true, 'message' => ''];
        try {
            $scholarships = $this->modelScholarships->where('program_id', $id)->getAll();
            $program = $this->modelProgram->getItem($id);
            $university = $this->modelSchool->getItem($program->getSchoolId());
            $result['html'] = $this->view->render('Tiimedu/partials/admission.ajax', [
                'scholarships' => $scholarships,
                'university' => $university,
                'program' => $program
            ], false);
        }
        catch(VSException $e) {
            $result['status'] = false;
            $result['message'] = $e->message();
            VSDebug::log($e->message());
        }
        VSJson::response($result);
    }
}