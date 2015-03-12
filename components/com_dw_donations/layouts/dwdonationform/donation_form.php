<?php
defined('JPATH_BASE') or die;

JHtml::_('behavior.formvalidation');

$form=$displayData['form'];
$donor=$displayData['donor'];
$beneficiary=$displayData['beneficiary'];

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
		<?php echo $form->getInput('country'); ?>
		</div>
	</div>


	<div class="uk-form-row uk-margin-small-top">
		<div class="uk-form-controls uk-text-right">
			<div onclick="jQuery('#jform_anonymous').attr('checked',!jQuery('#jform_anonymous').attr('checked'));" class="uk-display-inline-block" data-uk-tooltip title="<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ANONYMOUS_TOOLTIP'); ?>">
				<i class="uk-icon-question-circle uk-margin-small-right"></i><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ANONYMOUS'); ?>
			</div>
			<?php echo $form->getInput('anonymous'); ?>
		</div>
	</div>	

	<div>
		<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_AMOUNT'); ?>
	</div>

	<div class="uk-form-row uk-margin-remove">

		<div class="uk-form-controls uk-margin-small-top">
			<?php echo $form->getInput('amount'); ?>
		</div>
	</div>

	<p class="uk-text-muted uk-text-small">
		<?php echo JText::_('COM_DW_DONATIONS_FORM_AGREE_TO_TOS'); ?>
	</p>
	
	<div class="uk-form-row uk-margin-top">
		<div class="uk-form-controls">
			<button type="submit" class="validate uk-button uk-button-primary uk-button-large uk-width-1-1">
				
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
	  
	<p class="uk-text-center">
		<a class="payment-step-back"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_RETURN'); ?></a>
	</p>
	  
</div>

<div class="donation-modal uk-modal">
    <div class="uk-modal-dialog">
    	<a class="uk-modal-close uk-close"></a>
        <div class="donation-message">
        </div>    
    </div>
</div>

<script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery("#form-moneydonation").submit(function(event) {
				event.preventDefault();
				var form = jQuery(this),
			        formData = form.serialize(),
					formMethod = form.attr("method");
				
				formData += "&ajax=1";
				
				jQuery.ajax({
					type: formMethod,
					data: formData,
					timeout:10000,
					success:function(response){
						//console.log(response);
						var n_options={status:"danger",timeout:2000,pos:"top-center"};
						try{
							response=jQuery.parseJSON(response);
							if(response.success){
								if(response.data.success){
									var order_id="<?php echo (JFactory::getConfig()->get("sef")==1)?"?":"&" ?>orderId="+response.data.success.orderId;
									window.location.href="<?php echo  htmlspecialchars_decode(JRoute::_("index.php?option=com_dw_donations&view=dwdonationredirect",false)); ?>"+order_id;
								}else if(response.data.error){
									jQuery.UIkit.notify(response.data.error,n_options);
								}
							}else{
								jQuery.UIkit.notify(response.message,n_options);
							}
						}catch(e){
							//console.log(e);
							document.open();
							document.write(response);
							document.close();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						document.open();
						document.write(errorThrown);
						document.close();
					}
				});
            });
		});
</script>