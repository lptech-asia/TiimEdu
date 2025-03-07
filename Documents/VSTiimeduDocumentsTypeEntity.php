<?php
/**
 * Class VSTiimeduDocumentsTypeEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduDocumentsTypeEntity extends VSEntity
{
    private static $__instance = null;
    public $modelDocument = null;
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }
    
    public function __construct() {
        parent::__construct();
    }


    public function getDocuments()
    {
        $documents = [];
        if($this->modelDocument)
        {
            $user = $this->modelDocument->getLoggined();
            $documents = $this->modelDocument->where('type_id', $this->getId())->where('user_id', $user->getId())->getAll();
        }
        
        return $documents;
    }

    public function getDocumentsStudentBySchool($userId)
    {
        $documents = [];
        if($this->modelDocument)
        {
            $documents = $this->modelDocument->where('type_id', $this->getId())->where('user_id', $userId)->getAll();
        }
        
        return $documents;
    }
}