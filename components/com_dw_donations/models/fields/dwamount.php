<?php
/**
 * @version     1.0.0
 * @package     com_moneydonations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldDwAmount extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'dwamount';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	
	protected function getInput()
	{
		$html = array();

		// Initialize some field attributes.
		$class     = !empty($this->class) ? ' class="radio amnt ' . $this->class . '"' : ' class="radio"';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$readonly  = $this->readonly;

		// Start the radio field output.
		//$html[] = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . $disabled . ' >';
		
		$default = intval ( $this-> default );
		
		// Get the field options.
		$options = $this->getOptions();

		$html[] = '<div class="uk-button-group predefined-options-group uk-float-left" data-uk-button-radio>';
		
		
		// Build the radio field output.
		foreach ($options as $i => $option)
		{
			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';

			$disabled = !empty($option->disable) || ($readonly && !$checked);

			$disabled = $disabled ? ' disabled' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';
			$onchange = !empty($option->onchange) ? ' onchange="' . $option->onchange . '"' : '';
			
			
			$active = ( $default == intval($option->value) ) ? 'uk-active' : '' ;
			$html[] = '<a  href="#" data-amount="'.intval($option->value).'" class="uk-button uk-button-large uk-button-blank uk-margin-small-right '.$active.' '.$this->id.'_option option_value_'.intval($option->value).'">'.intval($option->value).'</a >';
			
			//$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '" value="'
				//. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $required . $onclick
				//. $onchange . $disabled . ' />';

			//$html[] = '<label for="' . $this->id . $i . '"' . $class . ' > ' . JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . ' </label>';

			$required = '';
		}
		
		$html[] = '</div>';
		
		$html[] = '<a class="uk-button uk-button-link other-amount-toggle other-amount uk-float-right" href="#"> '.JText::_('COM_DW_DONATIONS_FORM_AMOUNT_OTHER').'<i class="uk-icon-long-arrow-right uk-margin-small-left"></i></a>';
		$html[] = '<a class="uk-button uk-button-link other-amount-toggle other-amount-back uk-hidden uk-float-right" href="#"><i class="uk-icon-long-arrow-left uk-margin-small-right"></i>'.JText::_('COM_DW_DONATIONS_FORM_AMOUNT_OTHER_BACK').'</a>';
		

		$html[] = '<div class="amnt-changer uk-display-inline uk-hidden">';
		$html[] = '		<a type="button" class="uk-button uk-button-large uk-button-blank amnt-button amnt-decrease uk-active">-</a>';
		$html[] = '		<input type="text" id="'.$this->id.'_text" class="amnt-value uk-form-large uk-text-center" value="10" maxlength="3" size="3" />';
		$html[] = '		<a type="button" class="uk-button uk-button-large uk-button-blank amnt-button amnt-increase uk-active">+</a>';
		$html[] = '</div>';
		
		$html[] = '<input type="hidden" name="'.$this->name.'" value="" id="'.$this->id.'_custom" />';
		
		// End the radio field output.
		//$html[] = '</fieldset>';
		
		$html[] = '<script>';
		
		$html[] = 'jQuery(document).ready(function($){
			
						$("#donate-btn-amount").text("€"+'.$default.');
						
						jQuery(".jform_amount_option").click(function(e){
							
							e.preventDefault();
							
							if( $(this).hasClass("uk-active") )
								return false;

							var amount = $(this).attr("data-amount");
							
							$("#'.$this->id.'_custom").val(amount);
							
							$("#donate-btn-amount").text("€"+amount);

						});
						
						jQuery(".other-amount,.other-amount-back").click(function(e){
							
							e.preventDefault();
							
							$(".other-amount-toggle").toggleClass("uk-hidden");
							
							$(".predefined-options-group").toggleClass("uk-hidden");
							
							$(".amnt-changer").toggleClass("uk-hidden");
			
						});

						jQuery(".amnt-button").click(function(){
							
							var amount = parseInt ( $(".amnt-value").val() );
							
							if( $(this).hasClass("amnt-increase") && amount<999 )
							{
								amount++;
							}
							
							if( $(this).hasClass("amnt-decrease") && amount>1 )
							{
								amount--;
							}
	
							$(".amnt-value").val(amount);
							
							$("#'.$this->id.'_custom").val(amount);

							$("#donate-btn-amount").text("€"+amount);

						});
						
						jQuery("#form-moneydonation .uk-button").click(function() {
							if($("#'.$this->id.'_text").hasClass("invalid")){
								$("#'.$this->id.'").addClass("invalid").attr("aria-invalid", "true");
								$("#'.$this->id.'-lbl").addClass("invalid").attr("aria-invalid", "true");
							}
						});
						$("#'.$this->id.'_text").keyup(function(event){
							if((event.which>=48 && event.which<=57) || (event.which>=96 && event.which<=105)){
								$("#'.$this->id.'_custom").val($(this).val())
							}
							if ($.inArray(event.which, [8, 9, 27, 13, 46]) !== -1 ) {
								$("#'.$this->id.'_custom").val($(this).val())
							}
						});
						$("#'.$this->id.'_text").keydown(function (e) {
							// Allow: backspace, delete, tab, escape, enter
							if ($.inArray(e.keyCode, [8, 9, 27, 13, 46]) !== -1 ||
								 // Allow: Ctrl+A
								(e.keyCode == 65 && e.ctrlKey === true) || 
								 // Allow: home, end, left, right, down, up
								(e.keyCode >= 35 && e.keyCode <= 40)) {
									 return;
							}
							// Ensure that it is a number and stop the keypress
							if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
								e.preventDefault();
							}
						});
						$("#'.$this->id.'_text").focusout(function(e) {
               				if($(this).val()==""||$(this).val()==0){
								$(this).val("10");
							}
            			});
					});';

		$html[] = '</script>';
		
		return implode($html);
	}

	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];
			$tmp->onchange = (string) $option['onchange'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}