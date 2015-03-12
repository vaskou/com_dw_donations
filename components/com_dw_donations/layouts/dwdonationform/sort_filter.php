<?php

defined('JPATH_BASE') or die;

?>

<select class="uk-form-large uk-width ngo-sort" id="ngo_sort_filter" name="ngo_sort_filter">
	<option value="desc" data-stype="ngoPriority"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_SORT'); ?></option>
    <option value="asc" data-stype="ngoName"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_ALPHABETICAL_ASC');?></option>
    <option value="desc" data-stype="ngoName"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_ALPHABETICAL_DESC');?></option>
</select>