<?php

defined('JPATH_BASE') or die;

$user = JFactory::getUser();

//if(!empty($displayData)){
	$link = CRoute::_('index.php?option=com_community&view=profile&userid='.$displayData['ngo_id']);
?>

<div class="uk-panel">
	<div class="uk-grid">
		<div class="uk-width-2-6">
        	<a class="uk-thumbnail" href="<?php echo $link;?>" target="_blank" style="text-decoration:none !important;" title="<?php echo JText::_('COM_DW_DONATIONS_LIST_VIEW_PROFILE');?>" data-uk-tooltip>
				<img class="ngo_avatar" src="<?php echo $displayData['ngo_avatar'];?>" alt="<?php echo $displayData['ngo_name']; ?>" title="<?php echo $displayData['ngo_name']; ?>">
            </a>
		</div>
		<div class="uk-width-4-6">
			<div class="uk-text-large uk-text-right">
				<span class="uk-text-muted"><?php echo JText::_('COM_DW_DONATIONS_FORM_DONATION'); ?></span></br>
				<span class="ngo_name"><?php echo $displayData['ngo_name']; ?></span>
			</div>
			<?php
			if($user->guest){
			?>
			<div class="uk-text-right">
				<span><?php echo JText::_('COM_DW_DONATIONS_FORM_ALREADY_HAVE_AN_ACCOUNT');?></span>
				<?php echo JLayoutHelper::render(
					'popup.popup-button', 
					array (
						'buttonText' => JText::_('COM_DW_DONATIONS_FORM_LOGIN'),
						'buttonIcon' => '',
						'buttonType' => '',

						'layoutPath' => JPATH_ROOT .'/components/com_donorwiz/layouts',
						'layoutName' => 'user.login',
						'layoutParams' => array()
					), 
					JPATH_ROOT .'/components/com_donorwiz/layouts' , 
					null ); 
				?>
			</div>
            <?php } ?>
		</div>
	</div>
</div>

<?php
//}
?>