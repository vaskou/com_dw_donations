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
		$user = JFactory::getUser();
		$userId = $user->get('id');
		
		$donorwizUser=new DonorwizUser($userId);
		$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
		
		$this->isBeneficiaryDonations=$isBeneficiaryDonations;
		
		if($isBeneficiaryDonations){
			JRequest::setVar('beneficiary_id', $userId);
		}else{
			JRequest::setVar('donor_id', $userId);
		}
		
		$this->total = self::fn_get_donations_sum_by_user_id( $userId , $isBeneficiaryDonations );
		
	}
	
	public function fn_get_donations_sum_by_user_id($user_id,$is_beneficiary,$date=0)
	{
		$db = JFactory::getDBO();
		
		$query="SELECT SUM(amount) AS total_amount FROM #__dw_donations WHERE state=1";
		
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
		
		$db->setQuery($query);
        $db->Query();
		$result = $db->loadResult();
		
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
				'label'=>'Date'
			),
			array(
				'type'=>'number',
				'label'=>'Total amount'
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
