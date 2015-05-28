<?php

/**
 * @version     1.0.5
 * @package     com_moneydonations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
defined('_JEXEC') or die;

class DwDonationsHelper {
    
	public function fn_dwdonations_view_init()
	{
		$filter_array=array();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$jinput = JFactory::getApplication()->input;
		
		$donorwizUser=new DonorwizUser($userId);
		$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
		
		$this->isBeneficiaryDonations=$isBeneficiaryDonations;
		
		if($isBeneficiaryDonations){
			$filter_array['beneficiary_id']=$userId;
		}else{
			$filter_array['donor_id']=$userId;
		}

		$filter_array['state']=1;
		
		$input_filters=$jinput->get('filter','','ARRAY');
		if(is_array($input_filters)){
			$filter_array=array_merge($filter_array,$input_filters);
		}

		$jinput->set('filter', $filter_array);
		$jinput->set('filter_order', 'modified');
		$jinput->set('filter_order_Dir', 'desc');
		
		$this->total=self::fn_get_donations_sum_by_user_id($filter_array,$userId);

	}
	
	public function fn_get_donations_sum_by_user_id($filter_array=array(),$user_id=0)
	{
		$donorwizUser = new DonorwizUser ($user_id) ;
		$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
		$isDonor = $donorwizUser -> isDonor();
		
		if($isBeneficiaryDonations){
			$filter_array['beneficiary_id']=$user_id;
		}elseif($isDonor){
			$filter_array['donor_id']=$user_id;
		}else{
			$filter_array=array();
		}
		
		if(empty($filter_array)){
			return '0';
		}
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_donations/models', 'Dw_donationsModel');

		$donationsModel = JModelLegacy::getInstance('DwDonations', 'Dw_donationsModel',array('ignore_request' => true));
		foreach ($filter_array as $name => $value)
		{
			$donationsModel->setState('filter.' . $name, $value);
		}
		$result=$donationsModel->getSum();
		
		$result=(!empty($result))?$result:'0';
		
		return $result;
		
	}
	
	public function fn_get_annually_donations_sum_by_user_id($filter_array=array(),$user_id=0)
	{
		$donorwizUser = new DonorwizUser ($user_id) ;
		$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
		$isDonor = $donorwizUser -> isDonor();
		
		if($isBeneficiaryDonations){
			$filter_array['beneficiary_id']=$user_id;
		}elseif($isDonor){
			$filter_array['donor_id']=$user_id;
		}else{
			$filter_array=array();
		}
		
		if(empty($filter_array)){
			return '0';
		}
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_donations/models', 'Dw_donationsModel');

		$donationsModel = JModelLegacy::getInstance('DwDonations', 'Dw_donationsModel',array('ignore_request' => true));
		
		foreach ($filter_array as $name => $value)
		{
			$donationsModel->setState('filter.' . $name, $value);
		}
		$result=$donationsModel->getAnnualSum(array('YEAR(modified)','MONTH(modified)','DAY(modified)'));
		return $result;
	}

	public function fn_annually_chart_data_format($filter_array=array(),$user_id=0)
	{
		$data=self::fn_get_annually_donations_sum_by_user_id($filter_array,$user_id);
		
		$cols=array();
		$rows=array();
		
		if(!empty($data)){
			foreach($data as $k=>$v){
				$year=$v->year;
				$date='Date('.$v->year.','.($v->month - 1).','.$v->day.')';
				$rows[]=array(
					'c'=>array(					
						array('v'=>$date),
						array('v'=>$v->total_amount)
					)
				);
			}
		}
		
		$cols=array(
			array(
				'type'=>'date',
				'label'=>JText::_('COM_DW_DONATIONS_GRAPH_DATE')
			),
			array(
				'type'=>'number',
				'label'=>JText::_('COM_DW_DONATIONS_GRAPH_TOTAL_AMOUNT').' '.$year
			)
		);
		
		$result=array('cols'=>$cols,'rows'=>$rows);
		
		return $result;
	}
	
}
