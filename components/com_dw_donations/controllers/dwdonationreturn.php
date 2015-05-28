<?php

/**
 * @version     1.0.0
 * @package     com_moneydonations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_dw_donations/controller.php';

class Dw_donationsControllerDwDonationReturn extends Dw_donationsController {
	
	public function get_response()
	{
		
		$transactionData=array();
		
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
        $payments = $this->getModel('DwDonationForm', 'Dw_donationsModel');
		
		$donation_data=$app->getUserState('com_dw_donations.donation.data');
		if(!isset($donation_data)){
			JError::raiseError(400, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		
		$transactionId=$jinput->get('t');
		$orderCode=$jinput->get('s');
		$transactionData=$this->fn_viva_request_authorization($transactionId,$donation_data['beneficiary_id']);
		
		if(isset($transactionData['success'])){
			$transOrderCode=$transactionData['success']->Transactions[0]->Order->OrderCode;
			if($orderCode!=$transOrderCode){
				$error_message = 'Wrong OrderCode '.$orderCode;
				JLog::add($error_message, JLog::WARNING, 'get_response');
				JError::raiseError(400, JText::_('JERROR_ALERTNOAUTHOR'));
				return false;
			}
		}else{
			$error_message= ( isset($transactionData['error']) )? $transactionData['error'] : 'Wrong TransactionID '.$transactionId;
			JLog::add($error_message, JLog::WARNING, 'get_response');
			JError::raiseError(400, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		
		$order_code=array('order_code'=>$orderCode);
		if($order_code['order_code']!=$donation_data['order_code'])
		{
			$error_message = 'Wrong Session OrderCode '.$orderCode;
			JLog::add($error_message, JLog::WARNING, 'get_response');
			JError::raiseError(400, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;	
		}
		
		$time_updated = JFactory::getDate()->toSql();
		$payment = $payments->getTable();
		if($payment->load($order_code)){
			//var_dump($payment);
			if(empty($payment->transaction_id)){
				$data['id']=$payment->id;
				$data['transaction_id']=$transactionId;
				$data['state']=1;
				$data['modified']=$time_updated;
				$data['anonymous']=$payment->anonymous;
				$return=$payments->save($data);
				if ($return === false) {
					// ToDo: Error logging
					$error_message='Payment not saved: PaymentID='.$payment->id.' | OrderCode='.$payment->order_code.' | TransactionID='.$transactionId.' | Modified'.$time_updated;
					JLog::add($error_message, JLog::WARNING, 'get_response');	
				}
			}
		}else{
			// ToDo: Error logging
			$error_message='Payment could not load from table: PaymentID='.$payment->id.' | OrderCode='.$payment->order_code.' | TransactionID='.$transactionId.' | Modified'.$time_updated;
			JLog::add($error_message, JLog::WARNING, 'get_response');
		}
		
		
		/*Notify Donor and Beneficiary about the payment success --------------------------------------------------------------------*/
		JPluginHelper::importPlugin('donorwiz');
		$dispatcher	= JEventDispatcher::getInstance();
		$dispatcher->trigger( 'onDonationSuccess' , array( &$payment ) );
		
		$url = 'index.php?option=com_dw_donations&view=dwdonationsuccess' ;
		$app->setUserState('com_dw_donations.payment.data', json_encode($payment));
		$this->setRedirect(JRoute::_($url, false));

	}
	
	private function fn_viva_request_authorization($transactionId,$beneficiary_id)
	{
		
		$request =  VIVA_URL.'/api/transactions/';	
		
		$beneficiary = CFactory::getUser($beneficiary_id);
		
		// Your merchant ID and API Key can be found in the 'Security' settings on your profile.
		$MerchantId = $beneficiary->getInfo('FIELD_NGO_VIVA_MERCHANTID');
		$APIKey = $beneficiary->getInfo('FIELD_NGO_VIVA_APIKEY');
		//Set the ID of the Initial Transaction
		$request .= $transactionId;
		
		// Get the curl session object
		$session = curl_init($request);
		// Set query data here with the URL
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_USERPWD, $MerchantId.':'.$APIKey);
		curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 10); 
		$response = curl_exec($session);
		if($c_error=curl_error($session)){
			$response='{"Message":"'.$c_error.' TransactionID='.$transactionId.'"}';
		}
		curl_close($session);
		
		// Parse the JSON response
		try {
			$resultObj=json_decode($response);
		} catch( Exception $e ) {
			return array('error'=>$e->getMessage());	
		}
		
		if ( isset($resultObj->ErrorCode) ){
			if ($resultObj->ErrorCode === 0){
				return array('success'=>$resultObj);
			}
			else{
				return array('error'=>$resultObj->ErrorText);
			}
		}else{
			return array('error'=>$resultObj->Message);
		}
	
	}
}