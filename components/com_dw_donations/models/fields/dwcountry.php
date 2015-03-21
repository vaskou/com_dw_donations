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
class JFormFieldDwCountry extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'dwcountry';
	protected $ngo_objectives;
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	
	protected function getInput()
	{
		$html=array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}
		
		// @since 2.4 detect language and call current language country list
		if (!defined('COUNTRY_LANG_AVAILABLE')) {
		    define('COUNTRY_LANG_AVAILABLE', 1);
		}

		$lang = JFactory::getLanguage();
		$locale = $lang->getLocale();
		$countryCode = $locale[2];
		$countryLangExtension = "";

		$lang->load( 'com_community.country'); 

		$countryListLanguage =   explode(',', trim(COUNTRY_LIST_LANGUAGE) );
		if(in_array($countryCode,$countryListLanguage)==COUNTRY_LANG_AVAILABLE){
		    $countryLangExtension = "_".$countryCode;
		}
		jimport( 'joomla.filesystem.file' );
		$file	= JPATH_ROOT .'/components/com_community/libraries/fields/countries'.$countryLangExtension.'.xml';

		if( !JFile::exists( $file ) )
		{
			//default country list file
			$file = JPATH_ROOT .'/components/com_community/libraries/fields/countries.xml';
		}

		$contents	= JFile::read( $file );
		$parser		= new SimpleXMLElement($file,NULL,true);
		$document	= $parser->document;
		$countries		= $parser->countries;

        // build an array with TRANSLATED country names as keys...
        foreach($countries->country as $country){
            $name = (string) $country->name;
			$code = (string) $country->code;
            $countriesSorted[$name]['name'] = JText::_($name);
			$countriesSorted[$name]['code'] = JText::_($code);
        }

        // ...so it can be properly key-value natural-sorted
        uksort($countriesSorted, 'CStringHelper::compareAscii');
		
		$html[]='<select id="jform_'.$this->fieldname.'" name="'.$this->name.'"'.trim($attr).'>';
		$html[]='<option value="">'.JText::_('COM_COMMUNITY_SELECT_A_COUNTRY').'</option>';

		foreach($countriesSorted as $countrySorted)
		{
			$selected=($this->value==$countrySorted['code'])? 'selected="selected"' :'';
			$html[]='<option value="'.$countrySorted['code'].'" '.$selected.'>'.$countrySorted['name'].'</option>';
		}
		
		$html[]='</select>';
		$html[]='<script type="text/javascript">
					jQuery(document).ready(function($) {
						$.getJSON("http://www.telize.com/geoip?callback=?",function getgeoip(json){
							if("'.$this->value.'"==""){
								$("#jform_'.$this->fieldname.'").val(json.country_code);
							}
						});
					});
				</script>';
				
		return implode($html);
	}
}
