<?php
/**
 * Class VSTiimeduSchoolScholarshipsModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduSchoolScholarshipsModel extends VSModelBackend
{
    use CommonModel;
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_scholarships';
    protected $_primaryKey     = 'tiimedu_scholarships_id';
    protected $_fieldPrefix    = 'tiimedu_scholarships_';
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

    // deleteByProgramId
    public function deleteByProgramId($programId)
    {
        $this->massDelete([$this->_fieldPrefix. 'program_id' => $programId]);
    }
}