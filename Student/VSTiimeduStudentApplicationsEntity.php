<?php
/**
 * Class VSTiimeduStudentApplicationsEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduStudentApplicationsEntity extends VSEntity
{
    private static $__instance = null;
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }
    
    public function __construct() {}
    
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