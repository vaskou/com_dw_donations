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
		
		$transactionId=$jinput->get('t');
		$orderCode=$jinput->get('s');
		$transactionData=$this->fn_viva_request_authorization($transactionId);
		
		if(isset($transactionData['success'])){
			$transOrderCode=$transactionData['success']->Transactions[0]->Order->OrderCode;
			if($orderCode!=$transOrderCode){
				JError::raiseError(401, JText::_('JERROR_ALERTNOAUTHOR'));
				return false;
			}
		}
		
		
		$payment_data=$app->getUserState('com_dw_donations.payment.data');
		if(!isset($payment_data)){
			JError::raiseError(402, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		
		$order_code=array('order_code'=>$orderCode);
		if($order_code['order_code']!=$payment_data['order_code'])
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
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
				}
			}else{
				$menu = JFactory::getApplication()->getMenu();
				$item = $menu->getActive();
				$url = (empty($item->link) ? 'index.php?option=com_dw_donations&view=dwdonationsuccess' : $item->link);
				$app->setUserState('com_dw_donations.payment.data', json_encode($payment));
				$this->setRedirect(JRoute::_($url, false));
				return false;
			}
		}else{
			// ToDo: Error logging
		}
		
		
		//Notify Donor and Beneficiary about the payment success --------------------------------------------------------------------
		JPluginHelper::importPlugin('donorwiz');
		$dispatcher	= JEventDispatcher::getInstance();
		$dispatcher->trigger( 'onDonationSuccess' , array( &$payment ) );
		
		$url = 'index.php?option=com_dw_donations&view=dwdonationsuccess' ;
		$app->setUserState('com_dw_donations.payment.data', json_encode($payment));
		$this->setRedirect(JRoute::_($url, false));

	}
	
	private function fn_viva_request_authorization($transactionId)
	{
		
		$request =  VIVA_URL.'/api/transactions/';	
		
		// Your merchant ID and API Key can be found in the 'Security' settings on your profile.
		$MerchantId = '1ef183eb-94de-44dd-b682-3c404f74a267';
		$APIKey = 'vivavaskou';
		//Set the ID of the Initial Transaction
		$request .= $transactionId;
		
		// Get the curl session object
		$session = curl_init($request);
		// Set query data here with the URL
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_USERPWD, $MerchantId.':'.$APIKey);
		$response = curl_exec($session);
		curl_close($session);
		
		// Parse the JSON response
		try {
			$resultObj=json_decode($response);
		} catch( Exception $e ) {
			return array('error'=>$e->getMessage());	
		}
		
		if ($resultObj->ErrorCode==0){
			// print JSON output
			return array('success'=>$resultObj);
		}
		else{
			return array('error'=>$resultObj->ErrorText);
		}
	
	}
}