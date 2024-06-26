<?php

namespace PbStore\DiscountByCustomerGroup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;


class Config extends AbstractHelper
{
    protected $groupRepository;
    protected $searchCriteriaBuilder;

    public function __construct(
        Context $context,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }
    private const PATH_ENABLE = "discount_customer_group/general/enabled";
    private const PERSON = "discount_customer_group/discount_groups/groups_client_person";
    private const LEGAL_PERSON_FREE_TAX = "discount_customer_group/discount_groups/groups_client_legal_free_tax";
    private const LEGAL_PERSON__TAX = "discount_customer_group/discount_groups/groups_client_legal_tax";


    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue($this::PATH_ENABLE, ScopeInterface::SCOPE_STORE);
    }
    public function getDiscountByClientGroup($id)
    {
       switch ($id) {
           case 1:
               return $this->scopeConfig->getValue($this::PERSON, ScopeInterface::SCOPE_STORE);
           case 2:
               return $this->scopeConfig->getValue($this::LEGAL_PERSON_FREE_TAX, ScopeInterface::SCOPE_STORE);
           case 3:
               return $this->scopeConfig->getValue($this::LEGAL_PERSON__TAX, ScopeInterface::SCOPE_STORE);
       }
    }

    public function getAllCustomerGroups()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $customerGroups = $this->groupRepository->getList($searchCriteria);
        return $customerGroups->getItems();
    }
}
