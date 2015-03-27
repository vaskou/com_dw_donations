<?php
defined('JPATH_BASE') or die;

JHtml::_('behavior.formvalidator');

$form=$displayData['form'];
$donor=$displayData['donor'];
$beneficiary=$displayData['beneficiary'];
$session_donation_data=$displayData['session_donation_data'];
$anonymous=(isset($session_donation_data['anonymous']))?$session_donation_data['anonymous']:0;
$isPopup=( isset ( $displayData['isPopup'] ) ) ? $displayData['isPopup']  : false ;

?>



<form id="form-moneydonation" action="<?php echo JRoute::_('index.php?option=com_dw_donations&view=dwdonationform'); ?>" method="post" class="form-validate uk-form uk-form-stacked " enctype="multipart/form-data">

  
	<?php echo $form->getInput('id'); ?>
	<?php echo $form->getInput('state'); ?>
	<?php echo $form->getInput('created_by','',0); ?>
	<?php echo $form->getInput('created'); ?>
	<?php echo $form->getInput('modified'); ?>
	<?php echo $form->getInput('donor_id','',0); ?>
	<?php echo $form->getInput('beneficiary_id','',$beneficiary['ngo_id']); ?>

	<div class="uk-form-row uk-margin-top">
		<div class="uk-form-controls uk-width-1-1 uk-form-icon">
			<i class="uk-icon-user"></i>
			<?php echo $form->getInput('fname','',$donor['fname']); ?>
		</div>
	</div>

	<div class="uk-form-row uk-margin-small-top">
		<div class="uk-form-controls uk-width-1-1 uk-form-icon">
		<i class="uk-icon-user"></i>
		<?php echo $form->getInput('lname','',$donor['lname']); ?>
		</div>
	</div>
	
	<div class="uk-form-row uk-margin-small-top">
		<div class="uk-form-controls uk-width-1-1 uk-form-icon">
		<i class="uk-icon-envelope"></i>
		<?php echo $form->getInput('email','',$donor['email']); ?>
		</div>
	</div>

	<div class="uk-form-row uk-margin-small-top">
		<div class="uk-form-controls uk-width-1-1">
		<?php echo $form->getInput('country','',$session_donation_data['country']); ?>
		</div>
	</div>


	<div class="uk-form-row uk-margin-small-top">
		<div class="uk-form-controls uk-text-right">
			<div onclick="jQuery('#jform_anonymous').attr('checked',!jQuery('#jform_anonymous').attr('checked'));" class="uk-display-inline-block" data-uk-tooltip title="<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ANONYMOUS_TOOLTIP'); ?>">
				<i class="uk-icon-question-circle uk-margin-small-right"></i><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ANONYMOUS'); ?>
			</div>
			<?php echo $form->getInput('anonymous','',$anonymous); ?>
		</div>
	</div>	

	<div>
		<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_AMOUNT'); ?>
	</div>

	<div class="uk-form-row uk-margin-remove">

		<div class="uk-form-controls uk-margin-small-top">
			<?php echo $form->getInput('amount','',$session_donation_data['amount']); ?>
		</div>
	</div>

	<p class="uk-text-muted uk-text-small">
		<?php echo JText::_('COM_DW_DONATIONS_FORM_AGREE_TO_TOS'); ?>
	</p>
	
	<div class="uk-form-row uk-margin-top">
		<div class="uk-form-controls">
			<button type="submit" class="validate uk-button uk-button-primary uk-button-large uk-width-1-1" data-uk-modal="{target:'#loading-modal'}">
				
				<span class="uk-float-left uk-margin-small-right"><i class="uk-icon-long-arrow-right uk-margin-small-right"></i><?php echo JText::_('COM_DW_DONATIONS_FORM_BTN_DONATE');?></span>
				<span class="donate-btn-beneficiary uk-float-left"><?php echo $beneficiary['ngo_name'];?></span>
				<span id="donate-btn-amount" class="uk-float-right uk-text-bold"></span>
			</button>
		</div>

	</div>
		
	<input type="hidden" name="option" value="com_dw_donations" />
	<input type="hidden" name="task" value="dwdonationform.donate" />
	<?php echo JHtml::_('form.token'); ?>

</form>
<hr>
<div class="uk-width-1-1 uk-text-center">
	<i class="uk-icon-cc-visa uk-icon-medium"></i>
	<i class="uk-icon-cc-mastercard uk-icon-medium"></i>
	<i class="uk-icon-cc-amex uk-icon-medium"></i>
	<i class="uk-icon-cc-discover uk-icon-medium"></i>
	<p class="uk-text-muted uk-text-small uk-margin-remove">
		<?php echo JText::_('COM_DW_DONATIONS_FORM_ACCEPTED_CC'); ?>
	</p>
	<p class="uk-text-muted uk-text-small uk-margin-remove">
		<?php echo JText::_('COM_DW_DONATIONS_FORM_VIVA_PAYMENTS'); ?>
	</p>
	
    <?php if(!$isPopup){ ?>
        <p class="uk-text-center">
            <a class="payment-step-back"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_RETURN'); ?></a>
        </p>
    <?php } ?>
	  
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var redirect_url="<?php echo  htmlspecialchars_decode(JRoute::_("index.php?option=com_dw_donations&view=dwdonationredirect",false)); ?>";
		var order_id="<?php echo (JFactory::getConfig()->get("sef")==1)?"?":"&" ?>orderId=";
		fn_ngo_donate_button_submit(redirect_url,order_id);
		
	});
</script>

<div id="loading-modal" class="uk-modal" style="display:none;">

	<div class="uk-modal-dialog">
		
		<a class="uk-modal-close uk-close"></a>
		
		<div class="modal-content" data-uk-observe>
			
<!--				<div class="uk-text-center uk-margin-large-top spinner-wrapper">
					<i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i>
					<h3><?php echo JText::_('COM_DONORWIZ_MODAL_PLEASE_WAIT');?></h3>
				</div>-->
				<div class="layout-wrapper uk-hidden"></div>
				<?php echo JLayoutHelper::render('dwdonationform.redirect_layout', '' , JPATH_ROOT.COMPONENT_PATH.'/layouts'  ); ?>
			
		</div>
	
	</div>

</div>