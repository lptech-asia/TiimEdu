<?php
/**
 * Class VSTiimeduSchoolModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduSchoolModel extends VSModelBackend
{
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_school_information';
    protected $_primaryKey     = 'tiimedu_school_information_id';
    protected $_fieldPrefix    = 'tiimedu_school_information_';
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
        $this->_entity = VSEntity::getInstance()->load($this);
    }
}