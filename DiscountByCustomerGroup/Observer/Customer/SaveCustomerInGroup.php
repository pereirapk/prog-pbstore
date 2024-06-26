<?php

namespace PbStore\DiscountByCustomerGroup\Observer\Customer;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Event\ObserverInterface;

class SaveCustomerInGroup implements ObserverInterface
{
    protected $customerRepository;
    public function  __construct(
        CustomerRepository  $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $cnpj = $customer->getCustomAttribute('cnpj');
        $ie = $customer->getCustomAttribute('ie');
        $customerRepo = $this->customerRepository->getById($customer->getId());
        if($cnpj !== null){
            if ($ie !== null){
               $customerRepo->setGroupId(2);
               $this->customerRepository->save($customerRepo);
            }
            else {
                $customerRepo->setGroupId(3);
                $this->customerRepository->save($customerRepo);
            }
        }
    }
}
