<?php
/**
 * @version     1.1.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Dw_donations');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
