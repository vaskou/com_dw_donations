<?php

$app=JFactory::getApplication();
$jinput=$app->input;
$component = 'com_dw_donations';
$component_path = JPATH_ROOT.'/components/'.$component;

// Get/configure the users controller
if (!class_exists('Dw_donationsController')) 
	require($component_path.'/controller.php');

$config['base_path'] = $component_path;
$controller = new Dw_donationsController($config);

// Get the view and add the correct template path
$view =& $controller->getView('dwdonationform', 'html');
$view->addTemplatePath($component_path.'/views/dwdonationform/tmpl');

// Set which view to display and add appropriate paths
$jinput->set('view', 'dwdonationform');
$jinput->set('beneficiary_id', $displayData['beneficiary_id']);
$jinput->set('isPopup', $displayData['isPopup']);


JForm::addFormPath($component_path.'/models/forms');
JForm::addFieldPath($component_path.'/models/fields');



JFactory::getLanguage()->load($component, JPATH_SITE);

// And finally render the view!
$controller->display( );

?>