<?php
/**
 * Class VSTiimeduDocumentsModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduDocumentsModel extends VSModelBackend
{
    use CommonModel;
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_documents';
    protected $_primaryKey     = 'tiimedu_documents_id';
    protected $_fieldPrefix    = 'tiimedu_documents_';
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

    public function deleteItem($id)
    {
        $this->massDelete([$this->_primaryKey => $id, $this->_fieldPrefix. 'user_id' => $this->getLoggined()->getId()]);
    }
}