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

    public function getProgram()
    {
        if($this->modelProgram)
        {
            $program = $this->modelProgram->getItem($this->getProgramId());
            return $program;
        }
        return false;
    }


    // getScholarship
    public function getScholarship()
    {
        if($this->modelScholarship)
        {
            $scholarship = $this->modelScholarship->getItem($this->getScholarshipId());
            return $scholarship;
        }
        return false;
    }

    // count modelConverations by applicationId
    public function countConversations()
    {
        if($this->modelConversations)
        {
            $count = $this->modelConversations->where('application_id', $this->getId())->countItem();
            return $count;
        }
        return 0;
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
}