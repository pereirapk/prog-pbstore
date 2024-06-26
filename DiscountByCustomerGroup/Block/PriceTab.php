<?php

namespace PbStore\DiscountByCustomerGroup\Block;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\View\Element\Template\Context;
use PbStore\DiscountByCustomerGroup\Helper\Config;


class PriceTab extends Template
{
    protected $productRepository;
    protected $context;
    protected $product;
    protected $config;
    public function __construct(
        Context $context,
        ProductRepository $productRepository,
        ProductFactory $product,
        Config $config
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->productRepository = $productRepository;
        $this->product = $product;
        $this->config = $config;
    }

    protected function getProductId()
    {
        return $this->getRequest()->getParam('id');
    }
    public function getProductPrice()
    {
        $productId = $this->getProductId();
        $product = $this->product->create()->load($productId);

        return $product->getPriceInfo()->getPrice('final_price')->getValue();

    }
    public function getGroups()
    {
        $allCustomerGroups = $this->config->getAllCustomerGroups();
        array_shift($allCustomerGroups);
        return $allCustomerGroups;
    }
    public function calculatePrice($groupId)
    {
        $price = $this->getProductPrice();
        $price = $price - ($price * ($this->config->getDiscountByClientGroup($groupId) / 100));
        return $price;
    }
}
