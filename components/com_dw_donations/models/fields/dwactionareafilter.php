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
class JFormFieldDwActionAreafilter extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'dwactionareafilter';
	protected $ngo_areas;
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
		$ngo_area=$jinput->get('ngo_actionarea',0);
        $db = JFactory::getDBO();

		$areas_row=new CTableProfileField($db);
		$areas_row->load(array('fieldcode'=>'FIELD_ACTIONAREA'));
		$this->ngo_areas=explode("\n",$areas_row->options);
		
		if(isset($this->ngo_areas)){
			$html[]='<select class="uk-form-large ngo_filter uk-width" id="ngo_actionarea_list" name="ngo_actionarea">';
			$html[]='<option value="0">'.JText::_('COM_DW_DONATIONS_FORM_LBL_ACTIONAREA').'</option>';
			foreach($this->ngo_areas as $area){
				$selected=($ngo_area===$area)?'selected="selected"':'';
				$html[]='<option value="'.$area.'" '.$selected.'>'.JText::_($area).'</option>';
			}
			$html[]='</select>';
		}
        
		return implode($html);
	}
}