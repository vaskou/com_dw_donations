<?php

/**
 * @version     1.1.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;
define('COMPONENT_PATH','/components/com_dw_donations');

$viva_url = JComponentHelper::getParams('com_dw_donations')->get('viva_url');
define('VIVA_URL',$viva_url);

jimport('joomla.log.log');
JLog::addLogger(
	array(
		'text_file' => 'com_dw_donations.errors.php',
		'text_file_path' => 'logs'
	),
	JLog::ALL,
	array('donate','get_response')
);

jimport('joomla.application.component.controller');
include_once JPATH_ROOT.'/components/com_community/libraries/core.php';
require_once JPATH_ROOT . COMPONENT_PATH . '/helpers/dwdonationformhelper.php';
require_once JPATH_ROOT . COMPONENT_PATH . '/helpers/dwdonationshelper.php';

class Dw_donationsController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        //require_once JPATH_COMPONENT . '/helpers/dw_donations.php';
		require_once JPATH_ROOT . COMPONENT_PATH . '/helpers/dw_donations.php';
		
        $view = JFactory::getApplication()->input->getCmd('view', '');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }

}
