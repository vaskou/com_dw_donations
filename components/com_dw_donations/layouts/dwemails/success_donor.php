<?php

defined('JPATH_BASE') or die;

JFactory::getLanguage()->load( 'com_donorwiz');

$amount=$displayData['amount'];
$beneficiary=$displayData['beneficiary'];

?>

<?php echo JLayoutHelper::render( 'header' , array() , JPATH_ROOT .'/components/com_donorwiz/layouts/mail/common' , null );?>

<div class="main" style="background-color:#ff0f83;color:#ffffff;padding:10px 0 10px 0;">
<div style="max-width:480px;margin:0 auto;padding:10px;">
<h2 style="text-align:center;"><?php echo JText::_('COM_DW_DONATIONS_EMAIL_DONOR_THANK_YOU');?></h2>
<h1 style="text-align:center;"><?php echo JText::_('COM_DW_DONATIONS_EMAIL_DONOR_VALUABLE');?></h1>
<p style="text-align:center;"><?php echo JText::_('COM_DW_DONATIONS_EMAIL_DONOR_JUST_COMPLETED');?> <?php echo $amount;?> <?php echo JText::_('COM_DW_DONATIONS_EMAIL_DONOR_TO');?> <?php echo $beneficiary;?></p>
<h2 style="text-align:center;"><?php echo JText::_('COM_DW_DONATIONS_EMAIL_DONOR_VIVA_RECEIPT');?></h2>
</div>
</div>

<?php echo JLayoutHelper::render( 'social' , array() , JPATH_ROOT .'/components/com_donorwiz/layouts/mail/common' , null );?>
<?php echo JLayoutHelper::render( 'footer' , array() , JPATH_ROOT .'/components/com_donorwiz/layouts/mail/common' , null );?>