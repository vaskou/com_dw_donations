<?php

defined('_JEXEC') or die;

$jinput = JFactory::getApplication()->input;

?>

<h1 class="uk-text-center"><?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_TITLE');?></h1>
<p class="uk-text-center"><img src="https://www.vivapayments.com/Content/img/Home/logo.svg" alt="Viva Payments"></p>
<p class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i></p>
<p class="uk-text-large uk-text-center"><?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_PLEASE_WAIT');?></p>
<p class="uk-text-muted uk-text-center">
<?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_CLICK_HERE');?>
</p>
<p class="uk-text-center">
<a href="http://demo.vivapayments.com/web/newtransaction.aspx?ref=<?php echo $jinput->get('orderId','','cmd');?>">
<?php echo JText::_('COM_DW_DONATIONS_PAYMENT_REDIRECT_PAYMENT_PAGE');?>
</a>
</p>

<?php header('Refresh: 3; URL=http://demo.vivapayments.com/web/newtransaction.aspx?ref='.$jinput->get('orderId','','cmd')); ?>