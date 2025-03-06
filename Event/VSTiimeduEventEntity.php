<?php
/**
 * Class VSTiimeduEventEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduEventEntity extends VSEntity
{
    private static $__instance = null;
    public $modelMasterUser = null;
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