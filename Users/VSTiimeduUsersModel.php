<?php
/**
 * Class VSTiimeduUsersModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduUsersModel extends VSModelBackend
{
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
    }

    public function getByUserId($userId = null)
    {
        $count = $this->count("{$this->_fieldPrefix}user_id = '{$userId}'");
        if($count > 0)
        {
            $data =  $this->first([],["{$this->_fieldPrefix}user_id" => $userId]);
            return $this->parseEntity($data);
        }
        return false;
    }
}