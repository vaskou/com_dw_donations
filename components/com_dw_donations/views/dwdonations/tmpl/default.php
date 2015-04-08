<?php
/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser();
$userId = $user->get('id');

$data = array( 'items'=>$this->items,'pagination'=>$this->pagination , 'total'=> $this->total );

$filter_data = array( 'filterForm'=>$this->filterForm, 'pagination'=>$this->pagination );

echo JLayoutHelper::render('dwdonations.donations_filters', $filter_data, JPATH_ROOT.COMPONENT_PATH.'/layouts');

if($this->isBeneficiaryDonations){
	echo JLayoutHelper::render('dwdonations.beneficiary_donations_list', $data, JPATH_ROOT.COMPONENT_PATH.'/layouts');
}else{
	echo JLayoutHelper::render('dwdonations.donor_donations_list', $data, JPATH_ROOT.COMPONENT_PATH.'/layouts');
}