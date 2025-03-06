<?php
/**
 * Class VSTiimeduSchoolEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduSchoolEntity extends VSEntity
{
    private static $__instance = null;
    public $modelCountry = null;
    public $modelUser = null;
    public $modelProgram = null;
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

    public function countProgram()
    {
        if(!$this->modelProgram)
        {
            $count = $this->modelProgram->where('school_id',$this->getId())->countItem();
            return $count;
        }
        return 0;
        
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

    public function getCountry()
    {
        if($this->modelCountry)
        {
            $country = $this->modelCountry->getItem($this->getCountryId());
            return $country;
        }
        return false;
    }
}