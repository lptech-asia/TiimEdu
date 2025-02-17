<?php
/**
 * Class VSTiimeduUsersEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduUsersEntity extends VSEntity
{
    private static $__instance = null;
    const TYPE = [
        1 => 'student',
        2 => 'school',
    ];
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

    
}