<?php
/**
 * Class VSTiimeduSchoolProgramsEntity
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduSchoolProgramsEntity extends VSEntity
{
    private static $__instance = null;
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

    public function getIntake($i)
    {
        $name = "getIntake{$i}";
        return $this->$name();
    }

    public function getDeadline($i)
    {
        $name = "getDeadline{$i}";
        return $this->$name();
    }

    public function getFeeYear($i)
    {
        $name = "getFeeYear{$i}";
        return $this->$name();
    }
}