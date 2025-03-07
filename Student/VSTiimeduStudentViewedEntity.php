<?php
/**
 * Class VSTiimeduStudentViewedEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduStudentViewedEntity extends VSEntity
{
    private static $__instance = null;
    public $modelSchool = null;
    public $modelMasterUser = null;
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


    public function getUser()
    {
        if($this->modelMasterUser)
        {
            $user = $this->modelMasterUser->getItem($this->getUserId());
            return $user;
        }
        return false;
    }

    public function getSchool()
    {
        if($this->modelSchool)
        {
            $school = $this->modelSchool->getItem($this->getSchoolId());
            return $school;
        }
        return false;
    }
}