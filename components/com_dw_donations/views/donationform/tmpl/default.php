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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_dw_donations', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_dw_donations/assets/js/form.js');


if($this->item->state == 1){
	$state_string = 'Publish';
	$state_value = 1;
} else {
	$state_string = 'Unpublish';
	$state_value = 0;
}
$canState = JFactory::getUser()->authorise('core.edit.state','com_dw_donations');
?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
        jQuery(document).ready(function() {
            jQuery('#form-donation').submit(function(event) {
                
            });

            
			jQuery('input:hidden.donor_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('donor_idhidden')){
					jQuery('#jform_donor_id option[value="' + jQuery(this).val() + '"]').attr('selected', 'selected');
				}
			});
					jQuery("#jform_donor_id").trigger("liszt:updated");
			jQuery('input:hidden.beneficiary_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('beneficiary_idhidden')){
					jQuery('#jform_beneficiary_id option[value="' + jQuery(this).val() + '"]').attr('selected', 'selected');
				}
			});
					jQuery("#jform_beneficiary_id").trigger("liszt:updated");
        });
    });

</script>

<div class="donation-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-donation" action="<?php echo JRoute::_('index.php?option=com_dw_donations&task=donation.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
	</div>
	<div class="control-group">
		<?php if(!$canState): ?>
			<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
			<div class="controls"><?php echo $state_string; ?></div>
			<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
		<?php else: ?>
			<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
		<?php endif; ?>
	</div>

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
	</div>
				<?php echo $this->form->getInput('created'); ?>
				<?php echo $this->form->getInput('modified'); ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('donor_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('donor_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->donor_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="donor_id" name="jform[donor_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('beneficiary_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('beneficiary_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->beneficiary_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="beneficiary_id" name="jform[beneficiary_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('fname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('fname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('lname'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('lname'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('amount'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('amount'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('country'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('country'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('anonymous'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('anonymous'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('order_code'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('order_code'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('transaction_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('transaction_id'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('parameters'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('parameters'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
	</div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_dw_donations&task=donationform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_dw_donations" />
        <input type="hidden" name="task" value="donationform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
