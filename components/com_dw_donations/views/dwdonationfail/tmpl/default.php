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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_dw_donations');
$doc = JFactory::getDocument();

$app = Jfactory::getApplication();
$payment=$app->getUserState('com_dw_donations.payment.data');

if(isset($payment)){
	$payment=json_decode($payment);
}

$user  = CFactory::getUser( $payment->beneficiary_id );
$name = $user->getDisplayName();
$link = CRoute::_('index.php?option=com_community&view=profile&userid='.$payment->beneficiary_id);
$donation_link = JRoute::_('index.php?option=com_dw_donations&view=dwdonationform&beneficiary_id='.$payment->beneficiary_id);
$avatarUrl = $user->getThumbAvatar();


?>

<div class="uk-text-center">
	<h1><?php echo JText::_('COM_DW_DONATIONS_FAIL'); ?></h1>
	<img class="uk-thumbnail uk-border-circle" src="<?php echo $avatarUrl; ?>">
    <div class="uk-thumbnail-caption">
    	<a href="<?php echo $donation_link; ?>"><?php echo JText::_('COM_DW_DONATIONS_FAIL_NEW_DONATION').' '.$name; ?> </a><br/>
    	<a href="<?php echo $link; ?>"><?php echo JText::_('COM_DW_DONATIONS_SUCCESS_VIEW_PROFILE').' '.$name; ?> </a>
    </div>
    <?php 
		if($payment->donor_id==0){
	    	echo '<div>';
			echo '	<a href="'.JRoute::_('index.php?option=com_donorwiz&view=login&Itemid=314&mode=register').'">'.JText::_('JREGISTER').'</a>';
			echo '</div>';
		}
    ?>
</div>