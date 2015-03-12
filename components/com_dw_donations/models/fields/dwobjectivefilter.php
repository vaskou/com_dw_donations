<?php
/**
 * @version     1.0.0
 * @package     com_moneydonations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */

defined('JPATH_BASE') or die;

include_once JPATH_ROOT.'/components/com_community/libraries/core.php';

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldDwObjectivefilter extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'dwobjectivefilter';
	protected $ngo_objectives;
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
        
		$jinput = JFactory::getApplication()->input;
		$ngo_objective=$jinput->get('ngo_objective',0);
        $db = JFactory::getDBO();

		$objectives_row=new CTableProfileField($db);
		$objectives_row->load(array('fieldcode'=>'FIELD_OBJECTIVE'));
		$this->ngo_objectives=explode("\n",$objectives_row->options);
		
		if(isset($this->ngo_objectives)){
			$html[]='<select class="uk-form-large uk-width ngo_filter" id="ngo_objective_list" name="ngo_objective">';
			$html[]='<option value="0">'.JText::_('COM_DW_DONATIONS_FORM_LBL_OBJECTIVE').'</option>';
			foreach($this->ngo_objectives as $objective){
				$selected=($ngo_objective===$objective)?'selected="selected"':'';	
				$html[]='<option value="'.$objective.'" '.$selected.'>'.JText::_($objective).'</option>';
			}
			$html[]='</select>';
		}
        
		return implode($html);
	}
}