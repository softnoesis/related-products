<?php
/**
 * Softnoesis
 * Copyright(C) 12/2022 Softnoesis <contact@softnoesis.com>
 * @package Softnoesis_RelatedProduct
 * @copyright Copyright(C) 2015 Softnoesis (contact@softnoesis.com)
 * @author Softnoesis <contact@softnoesis.com>
 */
namespace Softnoesis\RelatedProduct\Block;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Directory\Model\CurrencyFactory;

class Module extends \Magento\Framework\View\Element\Template
{
    /**
     * @var layerResolver
     */
    private $layerResolver;
     /**
      * @var productCollectionFactory
      */
    protected $_productCollectionFactory;
     /**
      * @var categoryFactory
      */
    protected $_categoryFactory;
     /**
      * @var StoreManagerInterface
      */
    protected $_storeManager;
    /**
     * @var storeConfig
     */
    private $storeConfig;

    /**
     * @var CurrencyFactory
     */
    private $currencyCode;

    /**
     * Currency constructor.
     *
     * @param Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeConfig
     * @param CurrencyFactory $currencyFactory
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeConfig,
        CurrencyFactory $currencyFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->layerResolver = $layerResolver;
        $this->_categoryFactory = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->storeConfig = $storeConfig;
        $this->currencyCode = $currencyFactory->create();
    }
    /**
     * @return CurrentCategory
     */
    public function getCurrentCategory()
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
    
    /**
     * @return Current product catagory collection
     */
    public function getCurrentproductcatagory()
    {
         
        $Manager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $Manager->get('Magento\Framework\Registry')->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/
        foreach($categories as $category){
            $cat = $Manager->create('Magento\Catalog\Model\Category')->load($category);
                return $cat->getId();
        }
    }
    
    /**
     * @return Attributes values
     */
    public function getAttributesvalues()
    {
        $Manager = \Magento\Framework\App\ObjectManager::getInstance();
        $productcollection = $Manager->get('Magento\Framework\Registry')->registry('current_product');
    }
     /**
     * @return Current Product Id
     */
    public function getCurrentproductid()
    {
        $Manager = \Magento\Framework\App\ObjectManager::getInstance();
        $productidcollection = $Manager->get('Magento\Framework\Registry')->registry('current_product');
        return $productid = $productidcollection->getId();
    }
    /**
     * @return productcollection
     */
    public function getProductcollection()
    {
        $setpagesize = $this->_scopeConfig->getValue('Softnoesis_tab/general/productnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $categoryId = $this->getCurrentCategory()->getId();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $this->getCurrentproductcatagory()]);
        $collection->addAttributeToFilter('entity_id', array('neq' => $this->getCurrentproductid()));
        $collection->setPageSize($setpagesize);
        $collection->setOrder('position','DESC');
        return $collection;
    }


    public function getCatalogcollection()
    {
        $setpagesize = $this->_scopeConfig->getValue('Softnoesis_tab/general/productnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $categoryId = $this->getCurrentCategory()->getId();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $this->getCurrentproductcatagory()]);
        $collection->addAttributeToFilter('entity_id', array('neq' => $this->getCurrentproductid()));
        $collection->setPageSize($setpagesize);
        $collection->setOrder('position','ASC');
        return $collection;
    }

    /**
     * @return mediaurl
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                     ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    /**
     * @return url
     */
    public function getCurrentCategoryUrl()
    {
        return $this->getCurrentCategory()->getUrl();
    }
     /**
      * @return string
      */
    public function getSymbol()
    {
        $currentCurrency = $this->storeConfig->getStore()->getCurrentCurrencyCode();
        $currency = $this->currencyCode->load($currentCurrency);
        return $currency->getCurrencySymbol();
    }
    public function getConfigValue()
    {
        $myconfig = $this->_scopeConfig->getValue('Softnoesis_tab/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $myconfig;
    }
    public function getTitleValue()
    {
        $mycatalog = $this->_scopeConfig->getValue('Softnoesis_tab/general/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $mycatalog;
    }
}

