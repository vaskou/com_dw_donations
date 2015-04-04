<?php

defined('JPATH_BASE') or die;

$items = $displayData['items'];
$pagination = $displayData['pagination'];
$total = $displayData['total'];
$vivaURL = 'http://demo.vivapayments.com/web/receipt?tid=';

?>

<h1>

<?php if (count($items)) :?>
	<?php echo JText::_('COM_DW_DONATIONS_LIST_DONATIONS') ; ?>
<?php else :?>
	<?php echo JText::_('COM_DW_DONATIONS_LIST_NO_DONATIONS'); ?>
<?php endif;?>

</h1>

<?php if (count($items)) :?>
	<div class="uk-text-right uk-text-extra-large">
		<?php echo JText::_('COM_DW_DONATIONS_LIST_DONATIONS_TOTAL').': <span class="uk-text-primary">€'.$total.'</span>'; ?>
	</div>
	<hr>
<?php endif;?>

<?php if (count($items)) :?>

<ul class="uk-list uk-list-line">

<?php foreach($items as $k=>$item) : ?>

<?php if ( $item->state!=1 ) continue; ?>

<?php $beneficiary=JFactory::getUser( $item -> beneficiary_id ); ?>

<?php 	
	$item->currency_sign = '€';
?>

<li class="uk-panel uk-panel-box uk-panel-blank uk-panel-border uk-panel-shadow" >
	<div class="uk-grid">
	
		<div class="uk-width-3-4">
			<div class="uk-width-1-1">
				<span class="uk-text-large"><?php echo $beneficiary->name;?></span>
				<a target="_blank" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$item->beneficiary_id);?>">
				<i class="uk-icon-external-link"></i>
				</a>
			</div>
			<div class="uk-width-1-1 uk-text-muted">
				
				<?php echo JText::_('COM_DW_DONATIONS_LIST_FROM'); ?>
				<?php echo $item->lname.' '.$item->fname; ?>
				<?php echo '('.$item->email.')'; ?>

			</div>
			
			<div class="uk-width-1-1">
				<span class="uk-display-small-block uk-width-small-1-1">
					<i class="uk-icon-calendar"></i>
					<?php echo JFactory::getDate( $item->modified )->format('D, d M Y'); ?>
				</span>
				<i class="uk-icon-clock-o uk-margin-small-left"></i>
				<?php echo JFactory::getDate( $item->modified )->format('h:m'); ?> 
				<i class="uk-icon-map-marker uk-margin-small-left"></i>
				<?php echo $item->country; ?>
			</div>
				
			<div class="uk-width-1-1">
				<i class="uk-icon-print uk-text-primary"></i>
				<a href="<?php echo $vivaURL.$item->transaction_id;?>" target="_blank">
				<?php echo JText::_('COM_DW_DONATIONS_LIST_PRINT');?>
				</a>
			</div>
								
			<div class="uk-width-1-1">
				<i class="uk-icon-plus uk-text-primary"></i>
				<a href="<?php echo JROUTE::_('index.php?option=com_dw_donations&view=dwdonationform&Itemid=346&lang=el&beneficiary_id='.$item->beneficiary_id); ?>" target="_blank">
				<?php echo JText::_('COM_DW_DONATIONS_LIST_NEW');?>
				</a>
			</div>
			
			<?php if( $item -> anonymous == 1) : ?>				
				<div class="uk-text-muted">
					 <?php echo JText::_('COM_DW_DONATIONS_LIST_ANONYMOUS_TEXT');?>
				</div>
			<?php endif;?>
		
		</div>

		<div class="uk-width-1-4 uk-text-right uk-text-extra-large uk-text-medium-small">
			
			<?php echo $item->currency_sign.$item->amount; ?>
			
		</div>
	
	</div>
</li>
		
<?php endforeach;?>

</ul>

<?php echo $pagination->getPagesLinks(); ?>

<?php endif;?>