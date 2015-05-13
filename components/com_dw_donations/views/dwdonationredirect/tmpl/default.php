<?php

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$jinput = $app->input;

$returnfromviva=$app->getUserState('com_dw_donations.returnfromviva');

$donation=$app->getUserState('com_dw_donations.donation.data');

$ses_url=$app->getUserState('com_dw_donations.donation.refererUrl');

$ref_url=$jinput->server->get('HTTP_REFERER','','RAW');

if($ses_url!=$ref_url){
	if($ref_url=='' || $ses_url==''){
		$ref_url=$ses_url;
	}else{
		$ref_url='';
	}
}

if($returnfromviva===false){
	$app->setUserState('com_dw_donations.returnfromviva',true);
}else{
	if($ref_url){
		$app->redirect($ref_url);
	}else{
		$app->redirect(JRoute::_('index.php?option=com_dw_donations&view=dwdonationform&beneficiary_id='.$donation['beneficiary_id'], false));
	}
	$app->setUserState('com_dw_donations.donation.refererUrl',null);
	exit();
}
?>

<h1 class="uk-text-center"><?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_TITLE');?></h1>
<p class="uk-text-center"><img src="https://www.vivapayments.com/Content/img/Home/logo.svg" alt="Viva Payments"></p>
<p class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i></p>
<p class="uk-text-large uk-text-center"><?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_PLEASE_WAIT');?></p>
<p class="uk-text-muted uk-text-center">
	<?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_CLICK_HERE');?>
</p>
<p class="uk-text-center">
	<a href="<?php echo VIVA_URL; ?>/web/newtransaction.aspx?ref=<?php echo $jinput->get('orderId','','cmd');?>">
		<?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_PAYMENT_PAGE');?>
	</a>
</p>

<?php header('Refresh: 3; URL='.VIVA_URL.'/web/newtransaction.aspx?ref='.$jinput->get('orderId','','cmd')); ?>