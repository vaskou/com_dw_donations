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

JHtml::stylesheet(Juri::base().'components/com_dw_donations/assets/css/ajax_loader.css');
JHtml::stylesheet(Juri::base().'components/com_dw_donations/assets/css/wiz_form.css');
JHtml::_('jquery.framework');

JHtml::script(Juri::base().'components/com_dw_donations/assets/js/list.js');
JHtml::script(Juri::base().'components/com_dw_donations/assets/js/list.pagination.js');
JHtml::script(Juri::base().'components/com_dw_donations/assets/js/wizard_ajax.js');

$app = JFactory::getApplication();
$jinput = JFactory::getApplication()->input;

$page=$jinput->get('page',0);

$isPopup=$jinput->get('isPopup',0,'BOOLEAN');

$ngos_array=$this->ngos_array;

$donor_user = JFactory::getUser();
$donor  = CFactory::getUser($donor_user->get('id') );
$donor_fname=$donor->getInfo('FIELD_GIVENNAME');
$donor_lname=$donor->getInfo('FIELD_FAMILYNAME');
$donor_email=$donor->email;

$session_donation_data=null;

if($donation_data=$app->getUserState('com_dw_donations.donation.data')){
	$donor_fname=$donation_data['fname'];
	$donor_lname=$donation_data['lname'];
	$donor_email=$donation_data['email'];
	$session_donation_data=$donation_data;
	$app->setUserState('com_dw_donations.donation.data',null);
}

$donorUser=new DonorwizUser($donor->id);
$isDonorBeneficiary=$donorUser-> isBeneficiary('com_dw_donations');

$beneficiary_id=$jinput->get('beneficiary_id','');
$donorwizUser=new DonorwizUser($beneficiary_id);
$isBeneficiaryDonations = $donorwizUser-> isBeneficiary('com_dw_donations');
if(!$isBeneficiaryDonations){
	$beneficiary_id='';
}

$ngo_list_params=array(
	'beneficiary_id'=>$beneficiary_id,
	'objective'=>$this->form->getInput('objective'),
	'actionarea'=>$this->form->getInput('actionarea'),
	'ngo_list'=>$this->ngos_array
);

if($beneficiary_id!=''){
	$beneficiary_info_params=$this->ngos_array[$beneficiary_id];
}else{
	$beneficiary_info_params=false;
}

$donation_form_params=array(
	'form'=>$this->form,
	'donor'=>array(
		'fname'=>$donor_fname,
		'lname'=>$donor_lname,
		'email'=>$donor_email,
		'isBeneficiary'=>$isDonorBeneficiary
	),
	'beneficiary'=>$beneficiary_info_params,
	'session_donation_data'=>$session_donation_data,
	'isPopup'=>$isPopup
);

?>

<div class="uk-grid">
	
	<?php 
		if(!$isPopup){
			echo JLayoutHelper::render('dwdonationform.ngo_list',$ngo_list_params,JPATH_ROOT.COMPONENT_PATH.'/layouts'); 
		}
	?>
    <div class="uk-width-medium-1-1">
        <div class="uk-grid payment-step payment-step-2" style=" <?php if($beneficiary_id==''){ echo 'display:none;';}?>" data-step="2">
            <div class="uk-width-medium-1-1 ngo_info">
   				<?php echo JLayoutHelper::render('dwdonationform.beneficiary_info',$beneficiary_info_params,JPATH_ROOT.COMPONENT_PATH.'/layouts'); ?>
            </div>
            <div class="uk-width-medium-1-1">
   				<?php echo JLayoutHelper::render('dwdonationform.donation_form',$donation_form_params,JPATH_ROOT.COMPONENT_PATH.'/layouts'); ?>
            </div>
        </div>
    </div>
</div>
<div class="ngo-loader">
	<div class="ajax-loader-bg">
    </div>
	<div class="ajax-loader">
		<img src="<?php echo JUri::base().COMPONENT_PATH.'/assets/images/loader.gif';?>" />
    </div>
</div>

<script type="text/javascript">
jQuery(function($){
	
	var plus='<?php echo (JFactory::getConfig()->get('sef')==1)?'?':'&' ?>';
	var current_url='<?php echo  htmlspecialchars_decode(JRoute::_("index.php?option=com_dw_donations&view=dwdonationform",false)); ?>';
	fn_moneydonationwizard_init(current_url,plus,<?php echo $isPopup;?>);
	
});

</script>
