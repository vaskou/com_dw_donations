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
var_dump($input_filters);
		$jinput->set('filter', $filter_array);
		$jinput->set('filter_order', 'modified');
		$jinput->set('filter_order_Dir', 'desc');
		
		$this->total=self::fn_get_donations_sum_by_user_id($filter_array);
		
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_donations/models', 'Dw_donationsModel');

		$donationsModel = JModelLegacy::getInstance('DwDonations', 'Dw_donationsModel',array('ignore_request' => true));
		foreach ($filter_array as $name => $value)
		{
			$donationsModel->setState('filter.' . $name, $value);
		}
		$result=$donationsModel->getAnnualSum();	
	}
	
	public function fn_get_donations_sum_by_user_id($filter_array=array())
	{
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
		return $result;
		
	}
	
	
	public function fn_get_annually_donations_sum_by_user_id($user_id,$is_beneficiary,$date=0)
	{
		$db = JFactory::getDBO();
		
		$query="SELECT DATE_FORMAT(modified,'%Y') as year,DATE_FORMAT(modified,'%m') as month,SUM(amount) AS total_amount FROM #__dw_donations WHERE state=1 ";
		
		if($is_beneficiary){
			$query.=" AND beneficiary_id=".$user_id;
		}else{
			$query.=" AND donor_id=".$user_id;
		}

		if($date){
			if(isset($date['month']) && $date['month']){
				$query.=" AND EXTRACT(MONTH FROM modified)=".$date['month'];
			}
			if(isset($date['year'])  && $date['year']){
				$query.=" AND EXTRACT(YEAR FROM modified)=".$date['year'] ;
			}
		}
		
		$query.=" GROUP BY YEAR(modified),MONTH(modified) ";

		$db->setQuery($query);
        $db->Query();
		$result = $db->loadAssocList();
		
		return $result;
	}
	
	public function fn_annually_chart_data_format($user_id,$is_beneficiary,$date=0)
	{
		$data=self::fn_get_annually_donations_sum_by_user_id($user_id,$is_beneficiary,$date);
		
		$cols=array();
		$rows=array();
		
		$cols=array(
			array(
				'type'=>'string',
				'label'=>JText::_('COM_DW_DONATIONS_GRAPH_DATE')
			),
			array(
				'type'=>'number',
				'label'=>JText::_('COM_DW_DONATIONS_GRAPH_TOTAL_AMOUNT')
			)
		);
		
		foreach($data as $k=>$v){
			$rows[]=array(
				'c'=>array(					
					array('v'=>$v['year'].'-'.$v['month']),
					array('v'=>$v['total_amount'])
				)
			);
		}
		
		$result=array('cols'=>$cols,'rows'=>$rows);
		
		return $result;
	}
	
}
