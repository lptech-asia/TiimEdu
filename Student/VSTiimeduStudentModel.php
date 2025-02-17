<?php
/**
 * Class VSTiimeduStudentModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduStudentModel extends VSModelBackend
{
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_student_information';
    protected $_primaryKey     = 'tiimedu_student_information_id';
    protected $_fieldPrefix    = 'tiimedu_student_information_';
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