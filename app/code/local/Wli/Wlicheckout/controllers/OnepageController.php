<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Onepage controller for checkout
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once 'Mage/Checkout/controllers/OnepageController.php';
class Wli_Wlicheckout_OnepageController extends Mage_Checkout_OnepageController
{
    /**
     * Create order action
     */
    public function saveOrderAction()
    {
		$m= new Mage; 
		$version= substr($m->getVersion(),2,1);
		
		
		if($version > 7)
		{

			if (!$this->_validateFormKey()) {
				$this->_redirect('*/*');
				return;
			}

			if ($this->_expireAjax()) {
				return;
			}

			$result = array();
			try {
				$requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
				if ($requiredAgreements) {
					$postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
					$diff = array_diff($requiredAgreements, $postedAgreements);
					if ($diff) {
						$result['success'] = false;
						$result['error'] = true;
						$result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
						$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
						return;
					}
				}

				$data = $this->getRequest()->getPost('payment', array());
				if ($data) {
					$data['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT
						| Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
						| Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
						| Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
						| Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;
					$this->getOnepage()->getQuote()->getPayment()->importData($data);
				}

				$this->getOnepage()->saveOrder();

				// get order id and customer data for donation module by chirag
				
				$customerData = Mage::getSingleton('customer/session')->getCustomer();
				$customer_id = $customerData->getId();
				$customer_data = Mage::getModel('customer/customer')->load($customer_id)->getData();

				$order = new Mage_Sales_Model_Order();

				$incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();

				$donationamount = Mage::getSingleton('core/session')->getDonationAmount();

				$donationdata = array();
				$donationdata['order_id'] = $incrementId;
				$donationdata['donation_amount'] = $donationamount;
				$donationdata['customer_firstname'] = $customer_data['firstname'];
				$donationdata['customer_lastname'] = $customer_data['lastname'];
				$donationdata['customer_email'] = $customer_data['email'];

				Mage::getModel('donation/donation')->insert($donationdata,'donation');

				$redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();

				$result['success'] = true;
				$result['error']   = false;
			} catch (Mage_Payment_Model_Info_Exception $e) {
				$message = $e->getMessage();
				if (!empty($message)) {
					$result['error_messages'] = $message;
				}
				$result['goto_section'] = 'payment';
				$result['update_section'] = array(
					'name' => 'payment-method',
					'html' => $this->_getPaymentMethodsHtml()
				);
			} catch (Mage_Core_Exception $e) {
				Mage::logException($e);
				Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
				$result['success'] = false;
				$result['error'] = true;
				$result['error_messages'] = $e->getMessage();

				$gotoSection = $this->getOnepage()->getCheckout()->getGotoSection();
				if ($gotoSection) {
					$result['goto_section'] = $gotoSection;
					$this->getOnepage()->getCheckout()->setGotoSection(null);
				}
				$updateSection = $this->getOnepage()->getCheckout()->getUpdateSection();
				if ($updateSection) {
					if (isset($this->_sectionUpdateFunctions[$updateSection])) {
						$updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
						$result['update_section'] = array(
							'name' => $updateSection,
							'html' => $this->$updateSectionFunction()
						);
					}
					$this->getOnepage()->getCheckout()->setUpdateSection(null);
				}
			} catch (Exception $e) {
				Mage::logException($e);
				Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
				$result['success']  = false;
				$result['error']    = true;
				$result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
			}
			$this->getOnepage()->getQuote()->save();
			
			/**
			 * when there is redirect to third party, we don't want to save order yet.
			 * we will save the order in return action.
			 */
			if (isset($redirectUrl)) {
				$result['redirect'] = $redirectUrl;
			}

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}else
		{
				if ($this->_expireAjax()) {
						return;
					}

        $result = array();
        try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }
            if ($data = $this->getRequest()->getPost('payment', false)) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            $this->getOnepage()->saveOrder();
			
			// get order id and customer data for donation module by chirag
				
				$customerData = Mage::getSingleton('customer/session')->getCustomer();
				$customer_id = $customerData->getId();
				$customer_data = Mage::getModel('customer/customer')->load($customer_id)->getData();

				$order = new Mage_Sales_Model_Order();

				$incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();

				$donationamount = Mage::getSingleton('core/session')->getDonationAmount();

				$donationdata = array();
				$donationdata['order_id'] = $incrementId;
				$donationdata['donation_amount'] = $donationamount;
				$donationdata['customer_firstname'] = $customer_data['firstname'];
				$donationdata['customer_lastname'] = $customer_data['lastname'];
				$donationdata['customer_email'] = $customer_data['email'];

				Mage::getModel('donation/donation')->insert($donationdata,'donation');

            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if( !empty($message) ) {
                $result['error_messages'] = $message;
            }
            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            if ($gotoSection = $this->getOnepage()->getCheckout()->getGotoSection()) {
                $result['goto_section'] = $gotoSection;
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }

            if ($updateSection = $this->getOnepage()->getCheckout()->getUpdateSection()) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
    }
}
