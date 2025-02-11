<?php
/**
 * Class VSTiimeduInstall
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduInstall extends VSInstaller implements VSInstallerInterface
{
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
        $this->_setTitle("Tiimedu");
        $this->_setDescription("Tiimedu  - Module quản lý hồ sơ đăng ký du học cho website tuvanduhoc.org");
        $this->_setClass($this);
        $this->_setVersion("1.0");
        $this->_setCMSVersion("10.0");
        parent::__construct();
    }

    public function init()
    {
        // Adding menu
        $this->addMenu('Gff', 'tiimedu')->setIcon('edu')
        ;

        // Table schema
        // $this->_installerData->setVersion("1.0")
        //     ->addTable('tiimedu')->setPrimaryKey('id')->setInt(11)->unsigned()
        // ;
        parent::_init();
    }

    public function createData(){}
}