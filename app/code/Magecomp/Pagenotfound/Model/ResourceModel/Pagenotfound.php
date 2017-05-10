<?php
namespace Magecomp\Pagenotfound\Model\ResourceModel;

class Pagenotfound extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
    {
        $this->_init('mc_pagenotfound','mc_pagenotfound_id');
    }
}