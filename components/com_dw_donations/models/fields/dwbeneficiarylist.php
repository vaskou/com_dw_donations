<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

include_once JPATH_ROOT.'/components/com_community/libraries/core.php';
require_once JPATH_ROOT . '/components/com_dw_donations/helpers/dwdonationformhelper.php';
/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @since  11.1
 */
class JFormFieldDwBeneficiaryList extends JFormField
{

	protected $type = 'dwbeneficiarylist';
	protected $selected_ngo;
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
        
		$this->ngos=DwDonationFormHelper::fn_get_ngos_data();//var_dump($this);
		
		if(isset($this->ngos)){
			$html[]='<select id="'.$this->id.'" name="'.$this->name.'">';
			$html[]='<option value="0">'.JText::_('None').'</option>';
			$selected_ngo=$this->value;
			foreach($this->ngos as $ngo){
				$selected=($selected_ngo===$ngo['ngo_id'])?'selected="selected"':'';
				$html[]='<option value="'.$ngo['ngo_id'].'" '.$selected.'>'.$ngo['ngo_name'].'</option>';
			}
			$html[]='</select>';
			
			$html[]='<script>';
			$html[]='jQuery(function($){
				link="index.php?option=com_dw_donations&view=dwdonationform";
				$("#'.$this->id.'").change(function(){
					if($(this).val()!=="0"){
						$("#jform_link").val(link+"&beneficiary_id="+$(this).val());
					}else{
						$("#jform_link").val(link);
					}
				});
			});';
			$html[]='</script>';
		}
        
		return implode($html);
	}
}
