<?php
/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_ROOT .'/components/com_dw_donations/controller.php';

/**
 * Donations list controller class.
 */
class Dw_donationsControllerDwDonations extends Dw_donationsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Donations', $prefix = 'Dw_dwdonationsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function fn_get_annualy_chart_data()
	{
		JSession::checkToken() or die( 'Invalid Token' );
		
		$jinput = JFactory::getApplication()->input;
		
		$user = JFactory::getUser();
		$userId = $user->get('id');
		
		$donorwizUser=new DonorwizUser($userId);
		$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
		
		$date=$jinput->get('date','0','array');
		
		$year=DwDonationsHelper::fn_annually_chart_data_format($userId,$isBeneficiaryDonations,$date);
		
		echo new JResponseJson($year);
		exit;		
	}
}