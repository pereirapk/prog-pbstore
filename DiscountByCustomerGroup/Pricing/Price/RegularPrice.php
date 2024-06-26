<?php

namespace PbStore\DiscountByCustomerGroup\Pricing\Price;


use Magento\Catalog\Pricing\Price\RegularPrice as PriceRegular;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\SaleableInterface;
use PbStore\DiscountByCustomerGroup\Helper\Config;

class RegularPrice extends PriceRegular
{
    protected $customerSession;
    protected $config;
    public function __construct(
        SaleableInterface $saleableItem,
        $quantity,
        CalculatorInterface $calculator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        CustomerSession $customerSession,
        Config $config
        )
    {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);
        $this->customerSession = $customerSession;
        $this->config = $config;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getValue()
    {
        $customer = $this->customerSession->getCustomerData();


        if(!empty($customer)){
            $price = $this->product->getPrice();
            $priceInCurrentCurrency = $this->priceCurrency->convertAndRound($price);
            $this->value = $priceInCurrentCurrency ? (float)$priceInCurrentCurrency : 0;
            if ($customer->getGroupId() >= 2) {
                $this->value =$this->value - ($this->value * ($this->config->getDiscountByClientGroup($customer->getGroupId()) / 100));
            }
        }
        else if ($this->value === null) {
            $price = $this->product->getPrice();
            $priceInCurrentCurrency = $this->priceCurrency->convertAndRound($price);
            $this->value = $priceInCurrentCurrency ? (float)$priceInCurrentCurrency : 0;
        }

        return $this->value;
    }

}
