<?php
/**
 * Class VSTiimeduStudentApplicationsModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 17/02/2025 03:38:19
 */
class VSTiimeduStudentApplicationsModel extends VSModelBackend
{
    use CommonModel;
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_applications';
    protected $_primaryKey     = 'tiimedu_applications_id';
    protected $_fieldPrefix    = 'tiimedu_applications_';
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