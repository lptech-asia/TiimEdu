<?php
/**
 * Class VSTiimeduConversationsModel
 *
 * This class handles the conversations model for the Tiimedu module.
 * It provides methods to manage and interact with conversation data.
 *
 * @todo Add detailed method descriptions and usage examples.
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 1.0.0 12/02/2025
 */
class VSTiimeduConversationsModel extends VSModelBackend
{
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_conversations';
    protected $_primaryKey     = 'tiimedu_conversations_id';
    protected $_fieldPrefix    = 'tiimedu_conversations_';
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