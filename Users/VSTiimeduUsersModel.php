<?php
/**
 * Class VSTiimeduUsersModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduUsersModel extends VSModelBackend
{
    use CommonModel;
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_users';
    protected $_primaryKey     = 'tiimedu_users_id';
    protected $_fieldPrefix    = 'tiimedu_users_';
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
        $this->modelUser = VSModel::getInstance()->load('User');
    }

    public function getUser($type = 1)
    {
        $sql = "SELECT ms_user.* from {$this->_tableName} u JOIN {$this->modelUser->getTableName()} ms_user ON u.{$this->_fieldPrefix}user_id = ms_user.{$this->modelUser->getPrimaryKey()} WHERE {$this->_fieldPrefix}type = {$type} group BY {$this->modelUser->getPrimaryKey()}";
        $data = $this->getPagingByCustomSQL($sql,15,true);
        $students = $this->modelUser->parseEntities($data);
        return $students;
    }
}