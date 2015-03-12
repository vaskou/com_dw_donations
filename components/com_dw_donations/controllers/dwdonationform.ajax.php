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
	
	public function fn_get_beneficiary_info_ajax()
	{
		$jinput = JFactory::getApplication()->input;
		echo DwDonationFormHelper::fn_get_beneficiary_info_ajax($jinput->get('ngo_id'),'ajax');
	}
	
}
