<?php

use \Magento\Framework\App\Filesystem;

class Vreasy_ZendDbAdapters_Magentopdomysql extends \Magento\Framework\DB\Adapter\Pdo\Mysql
{

    public function __construct(array $config = []) {
        $this->_dirs = new \Magento\Framework\App\Filesystem(
            new \Magento\Framework\App\Filesystem\DirectoryList(Filesystem::ROOT_DIR),
            new \Magento\Framework\Filesystem\Directory\ReadFactory([]),
            new \Magento\Framework\Filesystem\Directory\WriteFactory([])
        );
        $this->string = new \Magento\Framework\Stdlib\String();
        $this->dateTime = new \Magento\Framework\Stdlib\DateTime();

        parent::__construct(
            $this->_dirs,
            $this->string,
            $this->dateTime,
            $config
        );
    }
}
