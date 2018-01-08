<?php

class Cammino_Uniquetaxvat_Model_Observer_Savetaxvat extends Varien_Object
{
    public function validate(Varien_Event_Observer $observer) {
        $customer = $observer->getEvent()->getCustomer();
        
        // applied only when changing taxvat
        if(($customer->getOrigData('taxvat') != $customer->getData('taxvat'))){
            $result = Mage::getModel('customer/customer')
                  ->getCollection()
                  ->addAttributeToSelect('taxvat')
                  ->addAttributeToFilter('taxvat',$customer->getData('taxvat'))->load();
            $count = $result->count();

            if ($customer->getId()) { // if it's edit of an existing customer
                if ($count > 1) // count > 1 because customer with that taxvat already exists                               
                    Mage::throwException('CPF já cadastrado!');      
            }
            else { // new customer           
                if ($count >= 1) // in a sign up, dont let new customers have a taxvat already stored
                    Mage::throwException('CPF já cadastrado!');
            }
        }
    }
}