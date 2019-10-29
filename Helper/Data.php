<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Landofcoder
 * @package    Lof_FacebookChat
 * @copyright  Copyright (c) 2018 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\FacebookChat\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;

    /**
     * @var TimezoneInterface
     */
    protected $_timezoneInterface;
    /**
     * @var customer
     */
    protected $customer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
     /**
     * @var PriceCurrencyInterface
     */
    protected $priceFormatter;

     /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context       $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param TimezoneInterface                           $timezoneInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        TimezoneInterface $timezoneInterface,
        PriceHelper $priceHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
        \Magento\Customer\Model\CustomerFactory $customer,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceFormatter,
        CustomerSession $customerSession
    ) {
        $this->_storeManager = $storeManager;
        $this->_dateTime = $dateTime;
        $this->_timezoneInterface = $timezoneInterface;
        $this->priceHelper = $priceHelper;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->customer = $customer;
        $this->priceFormatter = $priceFormatter;
        parent::__construct($context);
    }
     public function getProductById($product_id) {
        $collection = $this->productCollectionFactory->create()->load($product_id);
      return $collection;
    }

    public function getExpirationDate($membership_duration,$membership_unit) {
        if($membership_unit == 'day') {
            $time = $membership_duration*24*60*60;
        } elseif ($membership_unit == 'week') {
            $time = $membership_duration*7*24*60*60;
        } elseif ($membership_unit == 'month') {
            $time = $membership_duration*30*24*60*60;
        }elseif ($membership_unit == 'year') {
            $time = $membership_duration*30*24*60*60;
        }
        return $time;
    }
    /**
     * Get current currency code
     *
     * @return string
     */ 
    public function getCurrentCurrencyCode()
    {
      return $this->priceFormatter->getCurrency()->getCurrencyCode();
    }
     /**
     * Get current currency symbol
     *
     * @return string
     */ 
    public function getCurrentCurrencySymbol()
    {
      return $this->priceFormatter->getCurrency()->getCurrencySymbol();
    }
     public function getPriceFomat($price) {
        $currencyCode = $this->getCurrentCurrencyCode();
        return $this->priceFormatter->format(
                    $price,
                    false,
                    null,
                    null,
                    $currencyCode
                );
    }
    
      /**
      * Get customer id
      * @return customer id
      */
    public function getCustomerById($customer_id) {
        $collection = $this->customer->create()->load($customer_id);
        return $collection;
    }
     public function getCustomerId() {
        $customer = $this->customer->getCustomer();
       
        return $customer->getId();
    }
     /**
     * Return brand config value by key and store
     *
     * @param string $key
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
     public function getConfig($key, $store = null)
    {
       
        $store = $this->_storeManager->getStore($store);
        $result =  $this->scopeConfig->getValue(
            'facebookchat/'.$key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }

}
