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

$uri = JUri::getInstance();


?>

<?php if ($filters) : ?>

<form id="form-date-filter" action="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams(); ?>" method="get" class="form-validate uk-form uk-form-stacked " enctype="multipart/form-data">
	<?php if(JFactory::getConfig()->get("sef")!=1): ?>
        <input type="hidden" name="option" value="<?php echo $uri->getVar('option'); ?>"  />
        <input type="hidden" name="view" value="<?php echo $uri->getVar('view'); ?>"  />
        <input type="hidden" name="layout" value="<?php echo $uri->getVar('layout'); ?>"  />
        <input type="hidden" name="Itemid" value="<?php echo $uri->getVar('Itemid'); ?>"  />
        <input type="hidden" name="lang" value="<?php echo $uri->getVar('lang'); ?>"  />
    <?php endif ?>
	<?php echo $filters['filter_date_start']->input; ?>
    <?php echo $filters['filter_date_end']->input; ?>
    <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php echo JText::_('COM_DW_DONATIONS_FILTER_LABEL_BUTTON'); ?></button>
	<?php echo  $pagination->getLimitBox(); ?>
</form>    
    
<?php endif; ?>

