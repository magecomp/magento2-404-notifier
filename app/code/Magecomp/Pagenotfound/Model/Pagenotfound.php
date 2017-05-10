<?php
namespace Magecomp\Pagenotfound\Model;

class Pagenotfound extends \Magento\Framework\Model\AbstractModel implements PagenotfoundInterface, \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'mc_pagenotfound';
 
    protected function _construct()
    {
        $this->_init('Magecomp\Pagenotfound\Model\ResourceModel\Pagenotfound');
    }
 
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}