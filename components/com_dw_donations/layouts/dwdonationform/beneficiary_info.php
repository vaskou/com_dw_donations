<?php

defined('JPATH_BASE') or die;

?>

<?php if(!empty($displayData)) :?>

<div class="uk-panel">
	<div class="uk-grid">
		<div class="uk-width-2-6">
			<img class="uk-thumbnail" src="<?php echo $displayData['ngo_avatar'];?>" alt="">
		</div>
		<div class="uk-width-4-6">
			<div class="uk-text-large uk-text-right">
				<span class="uk-text-muted"><?php echo JText::_('COM_DW_DONATIONS_FORM_DONATION'); ?></span></br>
				<span><?php echo $displayData['ngo_name']; ?>
			</div>
			
			<div class="uk-text-right">
				<span><?php echo JText::_('COM_DW_DONATIONS_FORM_ALREADY_HAVE_AN_ACCOUNT');?></span>
				<?php echo JLayoutHelper::render(
					'popup-button', 
					array (
						'buttonText' => JText::_('COM_DW_DONATIONS_FORM_LOGIN'),
						'buttonIcon' => '',
						'buttonType' => '',

						'layoutPath' => JPATH_ROOT .'/components/com_donorwiz/layouts/user',
						'layoutName' => 'login',
						'layoutParams' => array()
					), 
					JPATH_ROOT .'/components/com_donorwiz/layouts/popup' , 
					null ); 
				?>
			</div>
		</div>
		</div>
	</div>
</div>

<?php endif;?>