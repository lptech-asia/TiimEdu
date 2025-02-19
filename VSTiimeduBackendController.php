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
        $this->model = VSModel::getInstance()->load($this);
        $this->modelUser = VSModel::getInstance()->load($this->model, 'Users/');
        $this->modelStudent = VSModel::getInstance()->load($this->model, 'Student/');
        $this->modelSchool = VSModel::getInstance()->load($this->model, 'School/');
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
        $this->view->render('Backend/universities');
    }
    
    // detail of university
    public function university()
    {
        $this->view->render('Backend/university');
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