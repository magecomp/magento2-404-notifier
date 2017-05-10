<?php
 
namespace Magecomp\Pagenotfound\Controller\Adminhtml\Pagenotfound;
 
class Index extends \Magento\Backend\App\Action
{
    
    protected $resultPageFactory;
    public function __construct(\Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
	
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magecomp_Pagenotfound::pagenotfound');
        $resultPage->addBreadcrumb(__('Magecomp'), __('Magecomp'));
        $resultPage->addBreadcrumb(__('Pagenotfound'), __('Pagenotfound'));
        $resultPage->getConfig()->getTitle()->prepend(__('404 Notifications'));
        return $resultPage;
    }
	
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magecomp_Pagenotfound::pagenotfound');
    }
}
