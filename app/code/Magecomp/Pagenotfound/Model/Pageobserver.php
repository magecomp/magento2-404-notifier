<?php
namespace Magecomp\Pagenotfound\Model;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\DataObject;

use \Psr\Log\LoggerInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magecomp\Pagenotfound\Model\PagenotfoundFactory;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;

class Pageobserver implements ObserverInterface
{
	/**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'pagenotfound_configuration/pagenotfoundoption/recipient_email';
	
	 /**
     * Email template config path
     */
    const XML_PATH_EMAIL_TEMPLATE = 'pagenotfound_configuration/pagenotfoundoption/email_template';
	
	/**
     * Sender email config path
     */
    const XML_PATH_EMAIL_SENDER = 'pagenotfound_configuration/pagenotfoundoption/sender_email_identity';
	
	protected $scopeConfig;
	protected $storeManage;
	protected $pagenotfoundFactory;
	protected $transportBuilder;
	protected $inlineTranslation;
		
	public function __construct(LoggerInterface $logger,ScopeConfigInterface $scopeConfig,StoreManagerInterface $storeManage, PagenotfoundFactory $pagenotfoundFactory, TransportBuilder $transportBuilder, StateInterface $inlineTranslation) 
	{
        $this->logger = $logger;
		$this->scopeConfig = $scopeConfig;
		$this->storeManage = $storeManage;
		$this->pagenotfoundFactory = $pagenotfoundFactory;
		$this->transportBuilder = $transportBuilder;
		$this->inlineTranslation = $inlineTranslation;
    }
 
    public function execute(\Magento\Framework\Event\Observer $observer)
    {  
		$id = $observer->getEvent()->getPage()->getIdentifier();
			
		$gloabal = $this->scopeConfig->getValue(\Magento\Cms\Helper\Page::XML_PATH_NO_ROUTE_PAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		
		$enable = $this->scopeConfig->getValue('pagenotfound_configuration/pagenotfoundoption/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
		if ($id == $gloabal && $enable) 
		{
			 $req = $observer->getEvent()->getControllerAction()->getRequest();
			 
			 //Get Current Store ID
			 $storeid = $this->storeManage->getStore()->getId();
			 
			// Get Current Requested Url
			$urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
			$url = $urlInterface->getCurrentUrl();
			
			//Save Data in Table
			$pagenotfound = $this->pagenotfoundFactory->create();
    		$pagenotfound->setData('store_id',$storeid)
						 ->setData('url',$url)
						 ->setData('client_ip',$req->getClientIp()) //Get Current Client Ip
						 ->setData('creation_date',date('Y-m-d H:i:s')) // Get Current Date and Time :
    			 		 ->save();
						 
			
			// Send Mail To Admin For This
			$this->inlineTranslation->suspend();
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->transportBuilder
               ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
			   ->setTemplateOptions(
                    [
                        'area' => 'frontend',
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
               ->setTemplateVars(['url' => $url, 'clientip' => $req->getClientIp(), 'reqdate' => date('Y-m-d H:i:s')])
               ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
               ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
               ->getTransport();

            $transport->sendMessage();
			$this->inlineTranslation->resume();
		}
    }	
}