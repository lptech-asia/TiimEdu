<?php
/**
 * Class VSTiimeduSchoolCountriesModel
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */
class VSTiimeduSchoolCountriesModel extends VSModelBackend
{
    use CommonModel;
    protected $_tableName      = TABLE_PREFIX . 'tiimedu_countries';
    protected $_primaryKey     = 'tiimedu_countries_id';
    protected $_fieldPrefix    = 'tiimedu_countries_';
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

    public function searchTitle($title = null)
    {
        $sql = "SELECT * FROM {$this->_tableName} where {$this->_fieldPrefix}name like {$this->doQuote('%' .$title. '%')}";
        $data = $this->query($sql);
        $items = $this->parseEntities($data);
        return $items;
    }
}