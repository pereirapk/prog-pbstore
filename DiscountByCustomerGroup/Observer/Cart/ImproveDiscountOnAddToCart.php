<?php

namespace PbStore\DiscountByCustomerGroup\Observer\Cart;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use IABA\Parcelas\Block\Product\View\Parcelas;
use Magento\Customer\Model\Session as CustomerSession;


class ImproveDiscountOnAddToCart implements ObserverInterface
{
    protected $customerSession;

    protected $parcelas;
    public function __construct(
        Parcelas $parcelas,
        CustomerSession $customerSession
    ) {
        $this->parcelas = $parcelas;
        $this->customerSession = $customerSession;
    }
    public function execute(Observer $observer)
    {
        // Retrieve quote item
        $item = $observer->getEvent()->getData('quote_item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);

        $customer = $this->customerSession->getCustomerData();

        $value = $item->getPrice();
        if (!empty($customer)){
            if ($customer->getGroupId() >= 2) {

                $discountAmount = ($value * ($this->getDiscountByGroup($customer->getGroupId()) / 100));
                $item->setCustomPrice($value - $discountAmount);
                $item->setOriginalCustomPrice($value - $discountAmount);
                $item->getProduct()->setIsSuperMode(true);
            }
        }

    }
    public function getDiscountByGroup($customerGroupId)
    {
        if($customerGroupId === 1){
            return (int)$this->parcelas->getDescontoGrupo01Preco();
        }
        elseif ($customerGroupId === 2){
            return (int)$this->parcelas->getDescontoGrupo02Preco();
        }
        else {
            return (int)$this->parcelas->getDescontoGrupo03Preco();
        }
    }
}
