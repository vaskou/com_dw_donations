<?php

defined('JPATH_BASE') or die;

$filter_data=$displayData['filterForm'];
$pagination=$displayData['pagination'];

$filters      = false;
if (isset($filter_data))
{
	$filters = $filter_data->getGroup('filter');
}

$donorwizUrl = new DonorwizUrl();

?>

<?php if ($filters) : ?>

<form id="form-date-filter" action="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams(); ?>" method="post" class="form-validate uk-form uk-form-stacked " enctype="multipart/form-data">
	<?php echo $filters['filter_date_start']->input; ?>
    <?php echo $filters['filter_date_end']->input; ?>
    <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php echo JText::_('COM_DW_DONATIONS_FILTER_LABEL_BUTTON'); ?></button>
    <?php echo  $pagination->getLimitBox(); ?>
</form>    
    
<?php endif; ?>

