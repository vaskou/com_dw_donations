<?php

defined('_JEXEC') or die;
$app=JFactory::getApplication();

$current_url=JURI::getInstance()->toString();
$app->setUserState('com_dw_donations.donation.refererUrl', $current_url);

$beneficiary_id=( isset ( $displayData['beneficiary_id'] ) ) ? $displayData['beneficiary_id']  : null ;
$isPopup=( isset ( $displayData['isPopup'] ) ) ? $displayData['isPopup']  : false ;

$donorwizUser=new DonorwizUser($beneficiary_id);
$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');

if(!$isBeneficiaryDonations){
	return false;
}

$styles=array(
	Juri::base().'components/com_dw_donations/assets/css/ajax_loader.css',
	Juri::base().'components/com_dw_donations/assets/css/wiz_form.css'
);
$scripts=array(
	Juri::base().'components/com_dw_donations/assets/js/list.js',
	Juri::base().'components/com_dw_donations/assets/js/list.pagination.js',
	Juri::base().'components/com_dw_donations/assets/js/wizard_ajax.js',
);

$popup_params=array (
	'isAjax' => true,
	'buttonLink' => JRoute::_('index.php?option=com_dw_donations&view=dwdonationform',false),
	'buttonText' => JText::_('COM_DW_DONATIONS_BTN_DONATE'),
	'buttonIcon' => '',
	'buttonType' => 'uk-hidden-small uk-button uk-button-primary',
	'layoutPath' => JPATH_ROOT .'/components/com_dw_donations/layouts',
	'layoutName' => 'dwdonationform.donation_form_view',
	'layoutParams' => array( 'beneficiary_id' => $beneficiary_id, 'isPopup' => $isPopup ),
	'styles' => $styles,
	'scripts' => $scripts
);

echo JLayoutHelper::render('popup.popup_button',$popup_params,JPATH_ROOT.'/components/com_donorwiz/layouts');

?>