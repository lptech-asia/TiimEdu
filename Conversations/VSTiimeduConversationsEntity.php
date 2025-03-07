<?php
/**
 * Class VSTiimeduConversationsEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduConversationsEntity extends VSEntity
{
    private static $__instance = null;
    public $modelUser = null;
    public $modelSchool = null;

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

    public function getSchool()
    {
        if($this->modelSchool)
        {
            $school = $this->modelSchool->getItem($this->getSchoolId());
            return $school;
        }
        return false;
    }

    public function getUser()
    {
        if($this->modelUser)
        {
            $user = $this->modelUser->getItem($this->getUserId());
            return $user;
        }
        return false;
    }

    public function getAttachmentName()
    {
        if($this->getAttachment())
        {
            $path = parse_url($this->getAttachment(), PHP_URL_PATH);
            $fileName = basename($path);
            return $fileName;
        }
    }
}