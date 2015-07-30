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
/**
 * @param	array	A named array
 * @return	array
 */
function Dw_donationsBuildRoute(&$query) {
    $segments = array();
	
	if(isset($query['view']) && $query['view']=='dwdonationform' && isset($query['beneficiary_id'])){
		$app=JFactory::getApplication();
		$menus=$app->getMenu();
		$menu=$menus->getItems('link','index.php?option=com_dw_donations&view=dwdonationform&beneficiary_id='.$query['beneficiary_id']);
		if(!empty($menu)){
			$query['Itemid']=$menu[0]->id;
			unset($query['view']);
			unset($query['beneficiary_id']);
			return $segments;
		}		
	}

    if (isset($query['task'])) {
        $segments[] = implode('/', explode('.', $query['task']));
        unset($query['task']);
    }
    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    }

    return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/dw_donations/task/id/Itemid
 *
 * index.php?/dw_donations/id/Itemid
 */
function Dw_donationsParseRoute($segments) {
    $vars = array();

    // view is always the first element of the array
	
    $vars['view'] = array_shift($segments);

    while (!empty($segments)) {
        $segment = array_pop($segments);
        if (is_numeric($segment)) {
            $vars['id'] = $segment;
        } else {
            $vars['task'] = $vars['view'] . '.' . $segment;
        }
    }

    return $vars;
}
