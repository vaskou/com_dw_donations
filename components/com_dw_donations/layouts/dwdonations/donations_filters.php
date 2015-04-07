<?php

defined('JPATH_BASE') or die;

$data=$displayData;

$filters      = false;
if (isset($data))
{
	$filters = $data->getGroup('filter');
}

$donorwizUrl = new DonorwizUrl();

?>

<?php if ($filters) : ?>

<form id="form-dwdonations" action="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams(); ?>" method="post" class="form-validate uk-form uk-form-stacked " enctype="multipart/form-data">
	<?php echo $filters['filter_date_start']->input; ?>
    <?php echo $filters['filter_date_end']->input; ?>
    <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php echo JText::_('COM_DW_DONATIONS_FILTER_LABEL_BUTTON'); ?></button>
</form>    
    
<?php endif; ?>
