<?php

/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_dw_donations/controller.php';

/**
 * Donation controller class.
 */
class Dw_donationsControllerDwDonationForm extends Dw_donationsController {

	public function donate(){
		
		$result=array();
		
		$jinput = JFactory::getApplication()->input;
		$app = JFactory::getApplication();
        $model = $this->getModel('DwDonationForm', 'Dw_donationsModel');
		
		$params=array('controller'=>$this,'model'=>$model,'jinput'=>$jinput);
		
		if(!JSession::checkToken()){
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>JText::_('JINVALID_TOKEN')),'params'=>$params));
			//jexit();
			return false;
		}
			
		$donation=$this->form_validate();
		if($donation===false){
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST')),'params'=>$params));
			//jexit();
			return false;
		}

		$request =  'http://demo.vivapayments.com/api/orders';	// demo environment URL
		//$request =  'https://www.vivapayments.com/api/orders';	// production environment URL
		
		// Your merchant ID and API Key can be found in the 'Security' settings on your profile.
		$MerchantId = '1ef183eb-94de-44dd-b682-3c404f74a267';
		$APIKey = 'vivavaskou'; 	
		
		//Set the Payment Amount
		$Amount = $donation['amount'].'00';	// Amount in cents
		
		//Set some optional parameters (Full list available here: https://github.com/VivaPayments/API/wiki/Optional-Parameters)
		$AllowRecurring = 'false'; // This flag will prompt the customer to accept recurring payments in tbe future.
		$RequestLang = 'en-US'; //This will display the payment page in English (default language is Greek)
		$SourceCode=8222;
		$ExpirationDate=date(DATE_ISO8601,strtotime("+ 5 days"));
		
		$postargs = 'Amount='.urlencode($Amount).'&AllowRecurring='.$AllowRecurring.'&RequestLang='.$RequestLang.'&SourceCode='.$SourceCode.'&FullName='.$donation['fname'].' '.$donation['lname'].'&Email='.$donation['email'].'&PaymentTimeOut=10800';
		
		// Get the curl session object
		$session = curl_init($request);
		
		
		// Set the POST options.
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_USERPWD, $MerchantId.':'.$APIKey);
		
		// Do the POST and then close the session
		$response = curl_exec($session);
		curl_close($session);
		
		//var_dump($response);
		/*var_dump(JFactory::getApplication()->input->getCmd('format'));*/
		
		// Parse the JSON response
		try {
			if(is_object(json_decode($response))){
				$resultObj=json_decode($response);	
			}else{
				throw new Exception("Result is not a json object");
			}
		} catch( Exception $e ) {
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>$e->getMessage()),'params'=>$params));
			//jexit();
			return false;
		}
		
		if(!isset($resultObj->ErrorCode)){
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>$response),'params'=>$params));
			//jexit();
			return false;
		}
		
		if ($resultObj->ErrorCode===0){	//success when ErrorCode = 0
			$orderId = $resultObj->OrderCode;
			
			$donation['order_code']=$orderId;
			// Attempt to save the data.
       		$return = $model->save($donation);
			if ($return === false) {
				// Save the data in the session.
				$app->setUserState('com_dw_donations.edit.donation.data', $donation);
				$msg=JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST');
				echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>$msg),'params'=>$params));
				//jexit();
				return false;
			}			
			
			// Check in the profile.
			if ($return) {
				$model->checkin($return);
			}
			// Save donation data
			$payment=json_encode($donation);
			$app->setUserState('com_dw_donations.payment.data', $payment);
			
			// Clear the profile id from the session.
			$app->setUserState('com_dw_donations.edit.donation.id', null);
			// Flush the data from the session.
	        $app->setUserState('com_dw_donations.edit.donation.data', null);
			
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('orderId'=>$orderId,'params'=>$params));
			//jexit();
			return false;
		}else{
			echo JLayoutHelper::render('dwdonationform.donation_redirect',array('error'=>array('error_text'=>$resultObj->ErrorText),'params'=>$params));
			//jexit();
			return false;
		}
	}
	
	private function form_validate(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('DwDonationForm', 'Dw_donationsModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);
		
		// Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_dw_donations.edit.donation.data', $jform, array());

            // Redirect back to the edit screen.
            //$id = (int) $app->getUserState('com_moneydonations.edit.moneydonation.id');
            $this->setRedirect(JRoute::_('index.php?option=com_dw_donations&view=dwdonationform', false));
            return false;
        }else{
			return $data;
		}
	}

}
